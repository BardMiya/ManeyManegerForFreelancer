import { ref, computed, reactive } from 'vue'
import { store } from '@/store/index'
import {cdate} from "cdate";

export function useCommonModule(){
    const yearFormat = 'YYYY'

    const thisYear = parseInt(cdate().format(yearFormat))

    /**
     * ■■ ref ■■
     */
    /**
     * 会計年度
     */
    const period = ref(thisYear)
    /**
     * Selected servicer
     */
    const servicer = ref('')

     /**
     * ■■ reactive ■■
     */
    /**
     * Servicer selectbox list
     */
    const servicerList = reactive([{name: '', personal_cd: null}])

    /**
     * ■■ computed ■■
     */
    /**
     * periodItem 
     */
    const periodComputed = computed({
        get: () => period.value,
        set: val => period.value = val
    })
    /**
     * periodList
     */
     const periodLisetComputed = computed({
        get: () => Array.from(Array(5).keys(), x => (thisYear - x))
    })
    /**
     * periodBegin
     */
    const periodBeginComputed = computed({
        get:() => `${periodComputed.value}-01-01`
    })
    /**
     * periodEnd
     */
    const periodEndComputed = computed({
        get:() => `${periodComputed.value}-12-31`
    })
    /**
     * servicer
     */
    const servicerComputed = computed({
        get: () => servicer.value,
        set: val => {
            servicer.value = val
        }
    })
    /**
     * servicerList
     */
    const servicerListComputed = computed({
        get: () => servicerList.map(item => ({title: item.name, value:item.personal_cd})),
        set: (val) => {Object.assign(servicerList, val)}
    })

    const isEmptyObj = (obj) => Object.keys(obj).length === 0
    return {
        periodComputed, 
        periodLisetComputed, 
        periodBeginComputed, 
        periodEndComputed,
        servicerComputed,
        servicerListComputed,
        isEmptyObj
    }
}