import { RestAccessor} from "@/composable/restAccessor"

export class ServicerAccessor extends RestAccessor{
    constructor(){
        super('/servicer')
    }
}
