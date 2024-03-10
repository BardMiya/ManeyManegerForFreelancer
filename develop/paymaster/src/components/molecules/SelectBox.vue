<template>
    <v-select 
        v-model="model"
        :items="items"
        :label="label"
        :disabled="disabled"
        :autofocus="autofocus"
        :density="density"
        :error="error"
        :errorMessages="errorMessages"
        :hideNoData="hideNoData"
        :hideSpinButtons="hideSpinButtons"
        :noDataText="noDataText"
        :placeholder="placeholder"
        :rules="rules"
        :validateOn="validateOn"
        variant="outlined"
        base-color="primary"
        bg-color="secondary"
        @click:clear="$emit('onClear')"
        @click:prepend="$emit('onPrepend')"
        @click:prependInner="$emit('onPrependInner')"
        @update:focused="$emit('onFocuse', $event)"  />
</template>
<script setup>
import { ref, computed } from 'vue'
const props = defineProps({
    modelValue:{
        type: [String, Number, Object],
        required: true,
    },
    items:{
        type: Array,
        required: true,
    },
    label:{
        type: String,
        required: true,
    },
    disabled:{
        type: Boolean,
        required: false,
        default: false,
    },
    autofocus:{
        type: Boolean,
        required: false,
        default: false,
    },
    density:{
        type: String,
        required: false,
        default: 'default'
    },
    error:{
        type: Boolean,
        required: false,
        default: false,
    },
    errorMessages:{
        type: [String, Array],
        required: false,
        default: [],
    },
    hideNoData:{
        type: Boolean,
        required: false,
        default: false,
    },
    hideSpinButtons:{
        type: Boolean,
        required: false,
        default: false,
    },
    noDataText:{
        type: String,
        required: false,
        default: 'No choice'
    },
    placeholder:{
        type: String,
        required: false,
        default: undefined,
    },
    rules:{
        type: Array,
        required: false,
        default: [],
    },
    validateOn:{
        type: String,
        required: false,
        default: undefined,
    }
})
const emit = defineEmits(['update:modelValue', 'change'])
const model = computed({
    get: () => props.modelValue,
    set: (val) =>{
        emit('update:modelValue', val)
        emit('change', val)
    }
})
</script>
