<template>
    <v-col>
        <v-row>
            <v-spacer />
            <v-col cols="3" align-self="start">{{$t('displayParts.revenue')}}</v-col>
            <v-col cols="3" align-self="start">{{ revenueComputed }}</v-col>
            <v-spacer />
        </v-row>
        <v-row>
            <v-spacer />
            <v-col cols="3" align-self="start">{{$t('displayParts.cost')}}</v-col>
            <v-col cols="3" align-self="start">{{ costComputed }}</v-col>
            <v-spacer />
        </v-row>
        <v-row>
            <v-spacer />
            <v-col cols="3" align-self="start">{{$t('displayParts.diff')}}</v-col>
            <v-col cols="3" align-self="start">{{ diffComputed }}</v-col>
            <v-spacer />
        </v-row>
        <v-data-table :headers="headerComputed" :items="statementDataComputed" :items-per-page="-1"/>
    </v-col>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n';
import { PeriodEndCloseAccessor } from '@/composable/api/periodEndClose'
import { useCommonModule } from '@/composable/commonModule'

const {isEmptyObj} = useCommonModule()
const {t} = useI18n()

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

const statement = ref([])
const accessor = reactive(new PeriodEndCloseAccessor())

const statementComputed = computed({
    get: () => statement.value.length == 0 ? [] : statement.value.find(e => e.transactioner == props.transactioner),
    set: val => statement.value = val
})
const headerComputed = computed({
    get: () => {
        const first = [{
            title: t('displayParts.account'),
            key: 'account'
        }]
        const accounts = statement.value.length == 0 ? [] : statementComputed.value.items.map( item => {
            return {
                title: item.item,
                key: item.item_cd
            }
        })
        const cols = first.concat(accounts)
        return cols
    }
})
const statementDataComputed = computed({
    get: () => {
        if(statement.value.length == 0){
            return []
        }
        const data = statementComputed.value
            .items.find(i => i.item_cd == '000')
            .details.map(detail => {
                return {
                    account: detail.name,
                    '000': detail.SUMMARY
                }
            })
        
        for(const item of statementComputed.value.items){
            for(const detail of item.details){
                data.find( a => a.account == detail.name )[item.item_cd] = Number(detail.SUMMARY).toLocaleString()
            }
        }
        return data
    }
})

const revenueComputed = computed({
    get: () => statement.value.length == 0 ? 0 : Number(statementComputed.value.revenue).toLocaleString()
})
const costComputed = computed({
    get: () => statement.value.length == 0 ? 0 : Number(statementComputed.value.cost).toLocaleString()
})
const diffComputed = computed({
    get: () => statement.value.length == 0 ? 0 : Number(statementComputed.value.diff).toLocaleString()
})
const onSelectedKey = async (period, transactioner) => {
    if(!(!period || !transactioner)){
        statementComputed.value = await accessor.get({id: `statement/${period.toString()}/${transactioner}`})
    }
}

/**
 * mounted
 */
 onMounted( async () => {
    // Set up Select box list
    onSelectedKey(props.period, props.transactioner)
})
/**
 * 公開メソッド
 */
defineExpose({
    onSelectedKey
})
</script>