import { RestAccessor} from "@/composable/restAccessor"

export class TransactionAccessor extends RestAccessor{
    constructor(){
        super('/transactions')
    }
}
