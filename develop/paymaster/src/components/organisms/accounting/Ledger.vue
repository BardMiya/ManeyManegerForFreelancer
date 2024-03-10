
<template>
    <v-container>
        <v-row>
            <v-col>
                <v-select label="台帳種別" 
                            v-model="ledgerItemComputed"
                            :items="ledgerListComputed" 
                            @update:modelValue="getTransaction(ledgerItemComputed)" />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <div id="HeadSetSpreadSheet" ref="refspreadsheet"></div>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-btn @click="save">save</v-btn>
                <v-btn @click="del">delete</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
    import { ref, reactive, computed, onMounted,getCurrentInstance,isReactive } from 'vue'
    import { useStore } from 'vuex'
    import { useRoute,useRouter } from 'vue-router'
    import { useI18n } from 'vue-i18n';
    import "jsuites/dist/jsuites.js"
    import "jsuites/dist/jsuites.css"
    import "jspreadsheet-ce/dist/jspreadsheet.css" 
    import jSpreadSheet from "jspreadsheet-ce"
    import { AccountAccessor } from '@/composable/api/accounts';
    import { ServicerAccessor } from '@/composable/api/servicer';
    import { TransactionAccessor} from '@/composable/api/transaction';

    const $router = useRouter()
    const $route = useRoute()
    const $store = useStore()
    const {t} = useI18n()
    const $this = getCurrentInstance().proxy
    const props = defineProps({
        isShow:{
            type: Boolean,
            required: true,
        },
        period:{
            type: Number,
            required: true
        },
        transactioner:{
            type: String,
            required: true
        },
        servicerList:{
            type: Array,
            required: true
        }
    })

    /**
     * ■■ ref ■■
     */
    /**
     * Spreadsheet reference
     */
    const refspreadsheet = ref(null)
    /**
     * Selected ledger
     */
    const ledgerItem = ref('')
    /**
     * Selected servicer
     */
    const servicer = ref('')
    /**
     * Transaction data
     */
    const vrHeadSets = ref( [] )
    /**
     * Ignoreed columns of event
     */
    const noeventCols = ref([0,7,11,12])

    /**
     * ■■ reactive ■■
     */
    /**
     * Ledger selectbox list
     */
    const ledgerList = reactive([{name: '', account_cd: null}])
    /**
     * Servicer selectbox list
     */
    const servicerList = reactive([{name: '', personal_cd: null}])
    /**
     * Jspreadsheets object
     */
    const jspreadsheetObj = reactive({})

    
    /**
     * ■■ computed ■■
     */
    /**
     * ledgerItem 
     */
     const ledgerItemComputed = computed({
        get: () => ledgerItem.value,
        set: val => {
            ledgerItem.value = val 
            // getTransaction(val)
        }
    })
    /**
     * ledgerList
     */
    const ledgerListComputed = computed({
        get: () => ledgerList.map(item => ({title: item.name, value:item.account_cd})),
        set: (val) => {Object.assign(ledgerList, val)}
    })
    /**
     * servicer
     */
    const servicerComputed = computed({
        get: () => props.transactioner,
    })
    const servicerListForCellComputed = computed({
        get: () => props.servicerList.map(item => ({name: item.name, id:item.personal_cd})),
    })
    /**
     * Jspreadsheet options
     */
    const jSpreadSheetOptins = computed(() => {
        return {
            //表の設定等
            //チェックボックスやカレンダー、プルダウンメニューも可能
            data: VRHeadSets.value,
            columns: [
                { type: "checkbox",title: " ", width: "25px"},
                { type: "numeric",title: "No.", width: "50px"},
                { type: "text",title: "取引日付", width: "100px", options:{ format:'YYYY/MM/DD' }},
                { type:"text", title:" ", width: "60px"},
                { type:"text", title:"勘定科目", width: "140px"},
                { type: "numeric", title: "借方", width: "80px", decimal:','},
                { type: "numeric", title: "貸方", width: "80px", decimal:','},
                { type: "numeric", title: "残高", width: "80px", decimal:','},
                { type: "text", title: "摘要 / 参照", width: "150px" },
                { type: "hidden",title: "transaction_no"},
                { type: "text" , title: "事業主", width: "100px"},
                { type: "hidden" , title: "account_cd"},
                { type: "hidden" , title: "json", width:"700px"}
            ],
            //INITIALIZATIONはここに書く https://bossanova.uk/jspreadsheet/v4/docs/quick-reference
            tableOverflow: true, // スクロールの有効化
            tableHeight: 1000,
            onafterchanges: changed
        }
    })
    /**
     * vrHeadSet
     */
    const VRHeadSets = computed({
        get: () => {
            const records = vrHeadSets.value.map(transaction => {
            const data = transaction.details
                .filter( detail => detail.account_CD != ledgerItemComputed.value)
                .map(detail => [
                    null,
                    null,
                    null,
                    detail.account_CD,
                    detail.account.name,
                    detail.type == 2 ? detail.price : null,
                    detail.type == 1 ? detail.price : null,
                    '',
                    detail.paper_no,
                    transaction.transaction_no,
                    '',
                    detail.account_CD,
                    '',
                ])
            data.unshift([
                false,
                transaction.transaction_no,
                transaction.transaction_date,
                null,
                null,
                null,
                null,
                null,
                transaction.remark,
                transaction.transaction_no,
                transaction.transactioner,
                '',
                '',
            ])
            return data
        }).flat(1)
        records.push([])
        return records
    },
        set: (val) => vrHeadSets.value = val
    })

    /**
     * ■■ methods ■■
     */
    /**
     * Event: Save button Click
     */
     const save = async () => {
        // getting a input records
        let records = null
        try{
            records = getUpdateRecords()
        }catch(e){
            alert(e.message)
            return
        }
        const register = new TransactionAccessor()


        // exect request transactions API by post or put
        for(const record of records){
            const trasactionNo = record.transaction_no === '' ? null : record.transaction_no

            // putting the finishing 
            const summary = record.details.reduce( ( sum, detail) => sum + (detail.price * (detail.type == '1' ? -1 : 1)), 0)
            record.details.push({
                type: summary < 0 ? '2' : '1',
                price: Math.abs(summary),
                account_CD: ledgerItemComputed.value,
                paper_no: record.details[0].paper_no
            })
            const updateRes = register.push(record, trasactionNo)
        }

        // reflesh a spreadsheet
        getTransaction(ledgerItemComputed.value)
    }
    /**
     * Event: Delete Click
     */
    const del = async () => {
         // getting a input records
         const remover = new TransactionAccessor()
         try{
            const records = getUpdateRecords()

            // exect request transactions API by delete
            for(const record of records){
                const trasactionNo = record.transaction_no
                await remover.delete({id:trasactionNo, parameter: record})
                // await useDeleteTransaction(trasactionNo, record)
            }
            getTransaction(ledgerItemComputed.value)
         }catch(e){
            alert(e.message)
         }
            
    }
    /**
     * Event: Changed Spreadsheet cell
     * @param {Object} sheet 
     * @param {Objcet} row 
     */
     const changed = (sheet, row)  => {
        if(!noeventCols.value.includes(parseInt(row[0].col))){
            const line = parseInt(row[0].row)
            jspreadsheetObj.setValue(`A${line + 1}`, true)
            setBalance(line)
            if(jspreadsheetObj.getValue(`C${line + 1}`)!=''){
                setHeaderRow(line)
            }else if(jspreadsheetObj.getValue(`D${line+1}`)!='' || jspreadsheetObj.getValue(`F${line+1}`)!='' || jspreadsheetObj.getValue(`G${line+1}`)!=''){
                setDetailRow(line)
                jspreadsheetObj.setValue(`L${line+1}`, `=D${line+1}`)
            }
        }
    }

    /**
     * Get checked rows
     */
    const getUpdateRecords = () => {
        // Selecting rows checked checkbox of A columns
        jspreadsheetObj.search("true")
        jspreadsheetObj.selectAll()
        let rows = null
        try{
            rows = jspreadsheetObj.getSelectedRows()
        }catch(e){
            alert(e.message)
            return
        }
        
        // Getting json string from spreadsheet
        const dataSource = rows.map( r => JSON.parse(r.lastChild.innerText.toLowerCase() ))
        const records = []

        try{
            dataSource.forEach( line => {
                if(line.hasOwnProperty('transaction_date')){
                    line.transactioner = line.transactioner.padStart(10, '0')
                    records.push(line)
                }else{
                    line.account_CD = line.account_cd.toUpperCase()
                    records[records.length-1].details.push(line)
                }
            })
        }catch(e){
            console.log(e)
            throw e
        }
        jspreadsheetObj.resetSearch()
        return records
    }
    /**
     * Gettng transaction data from RestAPI
     * @param {*} account 
     */
    const getTransaction = async (account) => {
        if(!account){
            return
        }
        const params = {account_CD: account, dateFrom: `${props.period}-01-01`, dateTo: `${props.period}-12-31`}
        if(servicerComputed.value != ''){
            params.transactioner = servicerComputed.value
        }
        const accessor = new TransactionAccessor()
        const transaction = await accessor.get({parameter: params})
        // const transaction = await useGetTransaction({parameter: params})
        VRHeadSets.value = transaction
        jspreadsheetObj.setData(VRHeadSets.value)
        if(VRHeadSets.value.length > 1){
            VRHeadSets.value.forEach((val, i) => {
                setBalance(i)
                if(jspreadsheetObj.getValue(`B${i+1}`)!=''){
                    setHeaderRow(i)
                }else if(jspreadsheetObj.getValue(`D${i+1}`)!='' || jspreadsheetObj.getValue(`F${i+1}`)!='' || jspreadsheetObj.getValue(`G${i+1}`)!=''){
                    setDetailRow(i)
                }
            })
        }
    }
    /**
     * 残高計算式をセット
     * @param {*} i 
     */
    const setBalance = (i) => {
        jspreadsheetObj.setValue(`H${i+1}`,`=IF(B${i+1},"",ABS(SUM(F1:F${i+1})-SUM(G1:G${i+1})))`,false)
    }
    /**
     * ヘッダ用行をセット
     * @param {number} i 行インデックス
     */
    const setHeaderRow = (i) => {
        const gray='#DDD'
        const back='background-color'
        jspreadsheetObj.setStyle(`A${i+1}`, back, gray)
        jspreadsheetObj.setStyle(`B${i+1}`, back, gray)
        jspreadsheetObj.setStyle(`C${i+1}`, back, gray)
        jspreadsheetObj.setStyle(`D${i+1}`, back, gray)
        jspreadsheetObj.getCell(`D${i+1}`).classList.add('readonly')
        jspreadsheetObj.setStyle(`E${i+1}`, back, gray)
        jspreadsheetObj.getCell(`E${i+1}`).classList.add('readonly')
        jspreadsheetObj.setStyle(`F${i+1}`, back, gray)
        jspreadsheetObj.getCell(`F${i+1}`).classList.add('readonly')
        jspreadsheetObj.setStyle(`G${i+1}`, back, gray)
        jspreadsheetObj.getCell(`G${i+1}`).classList.add('readonly')
        jspreadsheetObj.setStyle(`H${i+1}`, back, gray)
        jspreadsheetObj.getCell(`H${i+1}`).classList.add('readonly')
        jspreadsheetObj.setValue(`M${i+1}`, '=CONCATENATE("{",' +
                        `"\\"transaction_no\\":\\"", B${i+1},"\\",",` +
                        `"\\"transaction_date\\":\\"",C${i+1},"\\",",`+
                        `"\\"transactioner\\":\\"",K${i+1},"\\",",`+
                        `"\\"remark\\":\\"", I${i+1},"\\",",`+
                        '"\\"details\\":[]",'+
                        '"}")'
                    )
    }
    /**
     * 明細レコード用の行をセット
     * @param {number} i 
     */
    const setDetailRow = (i) => {
        jspreadsheetObj.getCell(`B${i+1}`).classList.add('readonly')
        jspreadsheetObj.getCell(`C${i+1}`).classList.add('readonly')
        jspreadsheetObj.setValue(`M${i+1}`, '=CONCATENATE("{",' +
            `"\\"type\\":\\"", IF(ISBLANK(F${i+1}),1,2),"\\",",` +
            `"\\"price\\":\\"",IF(ISBLANK(F${i+1}),G${i+1},F${i+1}),"\\",",`+
            `"\\"account_CD\\":\\"",L${i+1},"\\",",`+
            `"\\"paper_no\\":\\"", I${i+1},"\\"",`+
            '"}")'
        )
    }
    /**
     * mounted
     */
    onMounted( async () => {
        // Set up Select box list
        const accountGetter = new AccountAccessor()
        const accounts = await accountGetter.get({parameter: {type:[3,4,5,6]}})
        ledgerListComputed.value = accounts

        //インスタンス化
        Object.assign(jspreadsheetObj, jSpreadSheet(
            //DOM参照
            refspreadsheet.value,
            //表の設定データ
            jSpreadSheetOptins.value
        ))
    })
    const onSelectedKey = (period, transactioner) => {}
    /**
     * 公開メソッド
     */
    defineExpose({
        onSelectedKey
    })
</script>
<style>
.save-button{
    font-size: 1.2em;
    margin: 0;
    padding: 0;
}
</style>