<template>
    <v-overlay
      :model-value="isLoading"
      class="align-center justify-center"
    >
      <v-progress-circular
        color="primary"
        size="64"
        indeterminate
      ></v-progress-circular>
    </v-overlay>
</template>
<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'

    const $store = useStore()
    const isLoading = computed({
        get: () => $store.getters['isLoading']
    })
</script>