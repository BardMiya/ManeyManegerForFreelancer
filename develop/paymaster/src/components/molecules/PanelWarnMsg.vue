<template>
    <v-snackbar v-model="show" color="warning">
        {{message}}
        <template v-slot:actions>
            <v-btn :icon="mdiClose"
                    @click="$store.commit('clearMessage')"
          ></v-btn>
      </template>
    </v-snackbar>
</template>
<script setup>
    import { computed } from 'vue'
    import { useStore } from 'vuex'
    import { mdiClose } from '@mdi/js'
    
    const $store = useStore()

    const message = computed({
        get: () => $store.getters['showingWarn']
    })
    const show = computed({
        get: () => message.value != ''
    })
</script>