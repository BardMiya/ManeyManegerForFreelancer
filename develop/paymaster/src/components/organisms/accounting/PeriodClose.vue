<template>
    <v-row>
        <v-col><h2>{{ $t('displayParts.statement') }}</h2></v-col>
    </v-row>
    <v-row>
        <v-col>
            <v-data-table-virtual :headers="headers" 
                          :items="statementComputed" 
                          hide-action
                          hide-header
                          :items-per-page="-1" />
        </v-col>
    </v-row>
    <v-row>
        <v-col><h2>{{ $t('displayParts.balance') }}</h2></v-col>
    </v-row>
    <v-row>
        <v-col cols="6">
            <v-expansion-panels :model-value="Object.keys(leftComputed)"
                                multiple>
                <v-expansion-panel
                    v-for="group in leftComputed"
                    :key="group.type"
                >
                    <v-expansion-panel-title hide-actions class="line-item">
                        <span class="category-name">{{ group.category }}{{ $t('displayParts.ofClass')}}</span>
                        <div class="price-block r-text"><v-chip>{{ Number(group.summary).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-list>
                            <v-list-item v-for="detail in group.details">
                                <v-list-item-title hide-actions class="line-item">
                                    <span>{{ detail.name }}</span>
                                    <div><v-chip>{{ Number(detail.summary).toLocaleString() }}</v-chip></div>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
        <v-col cols="6">
            <v-expansion-panels :model-value="Object.keys(rightComputed)"
                                multiple>
                <v-expansion-panel
                    v-for="group in rightComputed"
                    :key="group.type">
                    <v-expansion-panel-title hide-actions class="line-item">
                        <span class="category-name">{{ group.category }}{{ $t('displayParts.ofClass')}}</span>
                        <div class="price-block r-text"><v-chip>{{ (-1 * Number(group.summary)).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-list>
                            <v-list-item v-for="detail in group.details">
                                <v-list-item-title class="line-item">
                                    <span>{{ detail.name }}</span>
                                    <div><v-chip>{{ (-1 * Number(detail.summary)).toLocaleString() }}</v-chip></div>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-expansion-panel-text>
                </v-expansion-panel>
                <v-expansion-panel>
                    <v-expansion-panel-title hide-actions class="line-item">
                        <span>{{ $t('displayParts.profit') }}</span>
                        <div><v-chip>{{ (-1* Number(profitComputed)).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
    </v-row>
    <v-row>
        <v-col>
            <v-expansion-panels>
                <v-expansion-panel>
                    <v-expansion-panel-title hide-actions class="line-item">
                        <span>{{ $t('displayParts.000') }}</span>
                        <div><v-chip>{{ Number(leftSummaryComputed).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
        <v-col>
            <v-expansion-panels>
                <v-expansion-panel>
                    <v-expansion-panel-title hide-actions class="line-item">
                        <span>{{ $t('displayParts.000') }}</span>
                        <div><v-chip>{{ (-1 * Number(rightSummaryComputed)).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
    </v-row>
    <v-row>
        <v-col>
            <v-btn @click="isConfirm = true"
                    :disabled="isClosedComputed">{{ `${period}${$t('displayParts.year')}を${$t('displayParts.confirm')}` }}</v-btn>
        </v-col>
        <v-col>
            <v-btn @click="isConfirmCarry = true"
                    :disabled="isCarryableComputed">{{ $t('displayParts.toNextPeriod') }}</v-btn>
        </v-col>
    </v-row>
    <confirm-message v-model="isConfirm"
                     :message="$t('message.QM0001', [period])" 
                     @execute="closePeriod(balance)" />
    <confirm-message v-model="isConfirmCarry"
                     :message="$t('message.QM0002', [period+1])" 
                     @execute="carrying(balance)" />
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n';
import {cdate} from "cdate"
import { useStore } from 'vuex'

import ConfirmMessage from '@/components/molecules/ConfirmMessage.vue'
import { PeriodEndCloseAccessor } from '@/composable/api/periodEndClose'
import { TransactionAccessor} from '@/composable/api/transaction';

const {t} = useI18n()
const $store = useStore()

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
    }
})
const emit = defineEmits(['periodClosed'])

const accessor = reactive(new PeriodEndCloseAccessor())
const isConfirm = ref(false)
const isConfirmCarry = ref(false)
const headers = ref([
    {title: t('displayParts.cost'), key: 'nameCost', align: 'start'},
    {title: t('displayParts.amount'), key: 'amountCost', align: 'end'},
    {title: t('displayParts.revenue'), key: 'nameRevenue', align: 'start' },
    {title: t('displayParts.amount'), key: 'amountRevenue', align: 'end'}
])
// const statement = ref([])
const balance = ref([])
const statement = ref([])
const statementComputed = computed({
    get: () => {
        if((balance ?? {value: []}).value.length == 0){
            return []
        }
        if(isClosedComputed.value){
            return afterClosed()
        }else{
            return beforeClosing()
        }
    },
    set: (val) => statement.value = val
})
const beforeClosing = () => {
    const costGroup = balance.value
            .find(group => group.type == 2) ?? {summary: 0, details:[]}
        const revenueGroup = balance.value
            .find(detail => detail.type == 1) ?? {summary: 0, details:[]}
        const diff = (-1 * revenueGroup.summary + -1 * costGroup.summary).toLocaleString()
        const cost = costGroup.details
            .map(detail => ({
                    nameCost: detail.name,
                    amountCost: Number(detail.summary).toLocaleString(),
                })
            )
        const revenue = revenueGroup.details
            .map(detail => ({
                        nameRevenue: detail.name,
                        amountRevenue: detail.summary,
                    })
                )
        revenue.forEach((detail, i) => {
            cost[i].nameRevenue = detail.nameRevenue
            cost[i].amountRevenue = (-1 * Number(detail.amountRevenue)).toLocaleString()
        })
        if(diff < 0){
            cost.push({
                nameRevenue: t('displayParts.loss'),
                amountRevenue: diff
            }) 
        } else {
            cost.push({
                nameCost: t('displayParts.profit'),
                amountCost: diff
            })
        }
        return cost
}
const afterClosed = () => {
    if(statement.value.length == 0){
        return []
    }
    const list = statement.value.filter(item => item.type == 1)
        .map(item => {
            return {
                nameCost: item.account.name,
                amountCost: Number(item.price).toLocaleString()
            }
        })
    statement.value.filter(item => item.type == 2)
        .forEach( (item, i) => {
            list[i].nameRevenue = item.account.name
            list[i].amountRevenue = Number(item.price).toLocaleString()
        })
    return list
}
const balanceComputed = computed({
    set: (val) => balance.value = val
})
const leftComputed = computed({
    get: () => balance.value.filter( r => r.type == 3)
})
const rightComputed = computed({
    get: () => balance.value.filter( r => [4, 5].some( v => v == r.type ))
})
const leftSummaryComputed = computed({
    get: () => {
        const init = 0
        const sum = leftComputed.value.reduce(
            (accumulator, currentValue) => accumulator + Number(currentValue.summary)
            ,init
        )
        return sum
    }
})
const rightSummaryComputed = computed({
    get: () => {
        const init = 0
        const sum = rightComputed.value.reduce(
            (accumulator, currentValue) => accumulator + Number(currentValue.summary)
            ,init
        )
        return sum + profitComputed.value
    }
})
const profitComputed = computed({
    get: () => {
        const revenueArray = balance.value.filter( r =>  r.type == 1)
        const costArray = balance.value.filter( r =>  r.type == 2)
        if(revenueArray.length == 0 || costArray.length == 0){
            return 0
        }
        const revenue = revenueArray[0]
        const cost = costArray[0]
        return Number(revenue.summary) + Number(cost.summary)
    }
})
const isClosedComputed = computed({
    get: () => balance.value.some(group => group.type == 6)
})
const isCarryableComputed = computed({
    get: () => props.period == cdate().get('year') 
        || $store.getters['accounting/carriedTransactioners']
            .some(item => item == props.transactioner)
})

const onSelectedKey = async (period, transactioner) => {
    if(!(!period || !transactioner)){
        // statementComputed.value = (await accessor.get({id: `statement/${period.toString()}/${transactioner}`})).find((e, i) => i == 0)
        const balanceData = await accessor.get({id: `balance/${period.toString()}/${transactioner}`})
        const res = (balanceData ?? [{balances:[]}])[0]
        balanceComputed.value  = res.barances
        if(isClosedComputed.value){
            const tranParams = {
                account_CD: 'SEK',
                transactioner: transactioner,
                dateFrom: `${period}/12/31`,
                dateTo: `${period}/12/31`
            }
            const register = new TransactionAccessor()
            const res = await register.get({parameter: tranParams})
            statement.value = res.find(item => item.details.some(detail => detail.account_CD != 'MIK')).details
        }
    }
}
const closePeriod = async statement => {
    const register = new TransactionAccessor()

    // 収益・経費の損益振替処理のパラメータ

    // 経費グループの集計結果抽出
    const costGroup = statement.find(group => group.type == 2)

    // 収益グループの集計結果抽出
    const revenueGroup = statement.find(group => group.type == 1)
    
    // 損益の算出
    const diff = (-1 * revenueGroup.summary) + (-1 * costGroup.summary)

    // 仕訳APIの明細リストの生成
    // 経費
    const debit = costGroup.details.map(detail => {
        return  {
                type: detail.summary >= 0 ? 1 : 2,
                price: Math.abs(detail.summary),
                account_CD: detail.account_cd,
                paper_no: null,
            }
    })
    // 収益
    const credit = revenueGroup.details.map( detail => {
        return {
                type:  detail.summary >= 0 ? 1 : 2,
                price: Math.abs(detail.summary),
                account_CD: detail.account_cd,
                paper_no: null,
        }
    })
    // 損益
    const profitLoss = {
        type: diff >= 0 ? 1 : 2,
        price: Math.abs(diff),
        account_CD: 'SEK',
        paper_no: null,
    }
    const transaction = debit.concat(credit)
    transaction.push(profitLoss)

    // 損益振替リクエストパラメータ
    const closeTransaction = {
        transaction_date: `${props.period}-12-31`,    // 期末の月日の設定は課題
        transactioner: props.transactioner,
        remark: t('dataItem.closeRemark'),
        details: transaction
    }

    // 損益の資本金振替処理のパラメータ
    const capitalParam = {
        transaction_date: `${props.period}-12-31`,    // 期末の月日の設定は課題
        transactioner: props.transactioner,
        remark: t('dataItem.closeRemark'),
        details: [
            {
                type: diff >= 0 ? 2 : 1,
                price: Math.abs(diff),
                account_CD: 'SEK',
                paper_no: null,
            },
            {
                type: diff >= 0 ? 1 : 2,
                price: Math.abs(diff),
                account_CD: 'SYT',
                paper_no: null,
            },
        ]
    }
    try{
        await register.push(closeTransaction)
        await register.push(capitalParam)
        $store.commit('info' , t('message.PM0000',[t('dataItem.closeRemark')]))
        onSelectedKey(props.period, props.transactioner)
    }catch(e){

    }
}
/**
 * 資本金グループを元入金に振替
 * 資産・負債残高を次期期首の繰越金として記帳
 */
const carrying = async (balance) =>{
    const register = new TransactionAccessor()


    // 資本グループの集計結果抽出
    const capitalGroup = balance.find(group => group.type == 5)

    // 負債・資本グループの集計結果抽出
    const balanceGroup = balance.filter(group => [3, 4].some(g => group.type == g))

    // 事業主貸・事業主借・所得（損益）の取得
    const shkTran = capitalGroup.details.find( detail => detail.account_cd == 'SHK')
    const htkTran = balanceGroup.find(group => group.type == 3)
        .details.find( detail => detail.account_cd == 'HTK')
    const sykTran = capitalGroup.details.find( detail => detail.account_cd == 'SYT')
    const mikBlc = capitalGroup.details.find( detail => detail.account_cd == 'MIK')
    const nextMik = Number(mikBlc.summary) 
        + Number(shkTran.summary) 
        + Number(sykTran.summary) 
        + Number(htkTran.summary)

    // 事業主貸・事業主借の元入金算入仕訳
    const carry = [
        [{
            type:  1,
            price: Math.abs(shkTran.summary),
            account_CD: 'SHK',
            paper_no: null,
        },
        {
            type:  2,
            price: Math.abs(shkTran.summary),
            account_CD: 'MIK',
            paper_no: null,
        }],
        [{
            type:  1,
            price: Math.abs(sykTran.summary),
            account_CD: 'SYK',
            paper_no: null,
        },
        {
            type:  2,
            price: Math.abs(sykTran.summary),
            account_CD: 'MIK',
            paper_no: null,
        }],
        [{
            type:  2,
            price: Math.abs(htkTran.summary),
            account_CD: 'MIK',
            paper_no: null,
        },
        {
            type:  1,
            price: Math.abs(htkTran.summary),
            account_CD: 'HTK',
            paper_no: null,
        }],
    ]
    //期末残高記録振替仕訳
    const fix = balanceGroup.map(group => 
        group.details.map(detail =>{
            if(['SHK','HTK'].some( v => v == detail.account_cd )){
                return 
            }
            return  [{
                type: group.type == 3 ? 1 : 2,
                price: Math.abs(detail.summary),
                account_CD: detail.account_cd,
                paper_no: null,
            },
            {
                type: group.type == 3 ? 2 : 1,
                price: Math.abs(detail.summary),
                account_CD: 'FIX',
                paper_no: null,
            }]
        }).filter(item => item != null)
    ).flat()
    //期首繰越記帳
    const next = balanceGroup.map(group => 
        group.details.map(detail =>{
            if(['SHK','HTK'].some( v => v == detail.account_cd )){
                return 
            }
            return  [{
                type: group.type == 3 ? 2 : 1,
                price: Math.abs(detail.summary),
                account_CD: detail.account_cd,
                paper_no: null,
            },
            {
                type: group.type == 3 ? 1 : 2,
                price: Math.abs(detail.summary),
                account_CD: 'MIK',
                paper_no: null,
            }]
        }).filter(item => item != null)
    ).flat()

    try{
        // 残高確定処理
        console.log(fix)
        for(const detail of fix.filter(item => item != null)){
            const closeTransaction = {
                transaction_date: `${props.period}-12-31`,    // 期末の月日の設定は課題
                transactioner: props.transactioner,
                remark: 'CLOSE',
                details: detail
            }
            await register.push(closeTransaction)
        }

        // 元入金振替処理
        for(const detail of carry.filter(item => item != null)){
            const capitalParam = {
                transaction_date: `${props.period}-12-31`,    // 期末の月日の設定は課題
                transactioner: props.transactioner,
                remark: 'CLOSE',
                details: detail
            }
            await register.push(capitalParam)
        }
        // 期首繰越記帳
        for(const detail of next.filter(item => item != null)){
            const nextParam = {
                transaction_date: `${props.period + 1 - 0}-01-01`,    // 期末の月日の設定は課題
                transactioner: props.transactioner,
                remark: 'OPEN',
                details: detail
            }
            await register.push(nextParam)
        }
        $store.commit('info' , t('message.PM0000',[t('displayParts.toNextPeriod')]))
        onSelectedKey(props.period, props.transactioner)
        emit('periodClosed')
    }catch(e){

    }

}
/**
 * mounted
 */
 onMounted( async () => {
    // Set up Select box list
    await onSelectedKey(props.period, props.transactioner)
})
/**
 * 公開メソッド
 */
 defineExpose({
    onSelectedKey
})
</script>