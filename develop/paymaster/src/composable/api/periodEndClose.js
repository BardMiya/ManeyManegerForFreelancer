import { RestAccessor} from "@/composable/restAccessor"

export class PeriodEndCloseAccessor extends RestAccessor{
    constructor(){
        super('/period-end')
    }
}
