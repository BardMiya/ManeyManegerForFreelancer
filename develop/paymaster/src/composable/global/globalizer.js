/**
 * 各種プラグインのGlobal化クラス
 * setupフック内でしかインスタンス化が許可されていないプラグインを
 * コンポーザブルからも使用可能とするため、
 * staticに保持する
 */
export class $g{
    /// GETTER ///
    /**
     * storeのGlobal化
     * @returns 
     */
    static store(){
        return $g._store
    }
    /**
     * I18nのGlobal化
     * @returns I18nのGrlobal化
     */
    static t(){
        return $g._t
    }
    /// SETTER ////
    /**
     * VuexのuseStoreで生成されたインスタンスをGlobalに保持
     * @param {Object} store 
     */
    static setStore(store){
        if(store.constructor.name != 'Store2'){
            throw `${store.constructor.name} let me down`
        }
        if($g._store == null){
            $g._store = store
        }else{
            throw 'Already set store.'
        }
    }
    /**
     * I118nのuseI18nで生成されたインスタンスをGlobalに保持
     * @param {Object} i18n 
     */
    static setI18n(i18n){
        console.log(i18n)
        if($g._t == null){
            $g._t = i18n
        }else{
            throw 'Already set i18n.'
        }
    }
}
$g._store = null
$g._t = null