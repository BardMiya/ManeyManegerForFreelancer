<template>
    <v-row>
        <v-col cols="6">
            <v-expansion-panels :model-value="Object.keys(leftComputed)"
                                multiple>
                <v-expansion-panel
                    v-for="group in leftComputed"
                    :key="group.type"
                >
                    <v-expansion-panel-title class="line-item">
                        <span class="category-name">{{ group.category }}{{ $t('displayParts.ofClass')}}</span>
                        <div class="price-block r-text"><v-chip>{{ Number(group.summary).toLocaleString() }}</v-chip></div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-list>
                            <v-list-item v-for="detail in group.details">
                                <v-list-item-title class="line-item">
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
                    <v-expansion-panel-title class="line-item">
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

const accessor = reactive(new PeriodEndCloseAccessor())

const balance = ref([])
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
        console.log(revenue)
        return Number(revenue.summary) + Number(cost.summary)
    }
})
const onSelectedKey = async (period, transactioner) => {
    if(!(!period || !transactioner)){
        const data = await accessor.get({id: `balance/${period.toString()}/${transactioner}`})
        const res = (data ?? [{barances:[]}])[0]
        balanceComputed.value  = res.barances
    }
}
/**
 * mounted
 */
 onMounted( async () => {
    onSelectedKey(props.period, props.transactioner)
})
/**
 * 公開メソッド
 */
 defineExpose({
    onSelectedKey
})
</script>
<style>
    .line-item{
        display: flex;
        justify-content: space-between;
   }
    .category-name{
        display: inline-block;
        width: 50%;
    }
    .price-block{
        display: inline-block;
        width: 50%;
    }
</style>