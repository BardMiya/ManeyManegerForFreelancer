<template>
    <v-container>
        <v-app-bar color="primary" density="compact">
            <template v-slot:prepend>
                <v-app-bar-nav-icon></v-app-bar-nav-icon>
            </template>
                    
            <v-app-bar-title>{{title}}</v-app-bar-title>
                    
            <template v-slot:append>
                <v-btn icon="mdi-dots-vertical"></v-btn>
            </template>
        </v-app-bar>
    </v-container>
</template>
<script setup>
    import { ref, reactive, computed } from 'vue'
    import { useRoute } from 'vue-router'
    import { useI18n } from 'vue-i18n';

    const $route = useRoute()
    const {t} = useI18n()

    /**
     * computed
     */
    const title = computed(() => t(`menu.${$route.name}`))

</script>