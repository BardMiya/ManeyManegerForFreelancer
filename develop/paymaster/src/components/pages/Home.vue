
<template>
    <v-item-group selected-class="bg-primary">
        <v-container>
            <v-row>
                <v-col v-for="name in menu" :key="name" cols="auto">
                    <v-item>
                        <v-card
                        :class="['d-flex align-center', selectedClass]"
                        :title="$t(`menu.${name}`)"
                        dark
                        height="100"
                        min-width="300"
                        @click="jump(name)" />
                    </v-item>
                </v-col>
            </v-row>
        </v-container>
  </v-item-group>
</template>
<script setup>
    import { ref, reactive, computed } from 'vue'
    import { useStore } from 'vuex'
    import { useRoute,useRouter } from 'vue-router'
    import { useI18n } from 'vue-i18n';

    const $router = useRouter()
    const $route = useRoute()
    const $store = useStore()
    const {t} = useI18n()
/**
 * reactive
 */
    
 /**
  * computed
  */
    const menu = computed(() => $store.getters['menu/pages'])
    const title = computed(() => t(`menu.${$route.name}`))
  /**
   * methods
   */
    const jump = (pageName) => {
        $router.push({ name: pageName })
    }

</script>
