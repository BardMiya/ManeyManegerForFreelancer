<template>
    <v-container>
        <v-row>
            <v-col>
                <select-box :label="$t('displayParts.period')" 
                            v-model="periodComputed"
                            :items="periodLisetComputed"
                            @change="onSelectedKey(periodComputed, servicerComputed)" />
            </v-col>
            <v-col>
                <select-box :label="$t('displayParts.transactioner')" 
                    v-model="servicerComputed"
                    :items="servicerListComputed"
                    @change="onSelectedKey(periodComputed, servicerComputed)" />
            </v-col>
        </v-row>
        <v-row>
            <v-card style="width: 100%">
                <v-tabs
                    v-model="tab"
                    color="deep-purple-accent-4"
                    align-tabs="center"
                    >
                    <v-tab :value="1">{{ $t('displayParts.transaction') }}</v-tab>
                    <v-tab :value="2">{{ $t('displayParts.ledgers') }}</v-tab>
                    <v-tab :value="3">{{ $t('displayParts.businessStatus') }}</v-tab>
                    <v-tab :value="4">{{ $t('displayParts.balanceCalc') }}</v-tab>
                    <v-tab :value="5">{{ $t('displayParts.periodClose') }}</v-tab>
                    </v-tabs>
            </v-card>
        </v-row>
    </v-container>
    <v-window v-model="tab">
        <v-window-item :value="1">
            <transaction ref="transactionCmp" 
                         :is-show="(tab == 1)" 
                         :period="periodComputed" 
                         :transactioner="servicerComputed" />
        </v-window-item>
        <v-window-item :value="2">
            <ledger ref="ledgerCmp" 
                    :is-show="(tab == 2)" 
                    :period="periodComputed" 
                    :servicer-list="servicerListComputed"
                    :transactioner="servicerComputed" />
        </v-window-item>
        <v-window-item :value="3">
            <statement ref="statementCmp" 
                       :is-show="(tab == 3)" 
                       :period="periodComputed" 
                       :transactioner="servicerComputed" />
        </v-window-item>
        <v-window-item :value="4">
            <balance ref="balanceCmp" 
                     :is-show="(tab == 4)" 
                     :period="periodComputed" 
                     :transactioner="servicerComputed" />
        </v-window-item>
        <v-window-item :value="5">
            <period-close ref="periodCloseCmp" 
                          :is-show="(tab == 5)" 
                          :period="periodComputed" 
                          :transactioner="servicerComputed" 
                          @periodClosed="pullCarried()" />
        </v-window-item>
    </v-window>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n';
import { useStore } from 'vuex'

import transaction from '@/components/organisms/accounting/Transaction.vue'
import ledger from '@/components/organisms/accounting/Ledger.vue'
import statement from '@/components/organisms/accounting/Statement.vue'
import balance from '@/components/organisms/accounting/Balance.vue'
import periodClose from '@/components/organisms/accounting/PeriodClose.vue'
import selectBox from '@/components/molecules/SelectBox.vue'
import { useCommonModule} from '@/composable/commonModule';
import { ServicerAccessor } from '@/composable/api/servicer';
import { TransactionAccessor} from '@/composable/api/transaction';
import {cdate} from "cdate"

const {t} = useI18n()
const $store = useStore()
const {periodComputed, periodLisetComputed, servicerComputed, servicerListComputed} = useCommonModule()



/**
 * 選択されているタグ番号
 */
const tab = ref(1)

/**
 * 子コンポーネント
 */
const transactionCmp = ref()
const ledgerCmp = ref()
const statementCmp = ref()
const balanceCmp = ref()
const periodCloseCmp = ref()
const selectedEvent = computed({
    get: () => [
        {onSelectedKey: (period, servicer) => {}},
        transactionCmp.value,
        ledgerCmp.value,
        statementCmp.value,
        balanceCmp.value,
        periodCloseCmp.value,
    ]
})

/**
 * ■■ reactive ■■
 */
const carriedTransaction = ref([])
/**
 * ■■ computed ■■
 */
const isCarried = computed({
    get: () => $store.dispatch('accounting/isCarried', servicerComputed),
    set: (val) => {
        $store.commit('accounting/carriedTransaction', val)
    }
})

/**
 * ■■ function ■■
 */
const onSelectedKey = (period, servicer) =>{
    (selectedEvent.value[tab.value] ?? selectedEvent.value[0]).onSelectedKey(period, servicer)
    console.log(selectedEvent.value[tab.value])
}
const pullCarried = async () => {
    const transactionGetter = new TransactionAccessor()
    const nowPeriodParam = {
                account_CD: 'MIK',
                dateFrom: `${cdate().get('year')}/01/01`,
                dateTo: `${cdate().get('year')}/01/01`
    }

    isCarried.value = await transactionGetter.get({parameter: nowPeriodParam})
}
/**
 * mounted
 */
 onMounted( async () => {
    // Set up Select box list
    const servicerGetter = new ServicerAccessor()
    const resServicer = await servicerGetter.get({})
    servicerListComputed.value = resServicer
    pullCarried()
})
</script>
