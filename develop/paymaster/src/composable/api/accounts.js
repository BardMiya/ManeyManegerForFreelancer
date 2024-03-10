import { RestAccessor} from "@/composable/restAccessor"

export class AccountAccessor extends RestAccessor{
    constructor(){
        super('/account')
    }
}
