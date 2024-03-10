<template>
    <v-dialog v-model="model"
              max-width="400"
              persistent>
      <v-card prepend-icon="mdi-map-marker"
              :text="message"
              :title="title">
        <template v-slot:actions>
          <v-spacer></v-spacer>

          <v-btn @click="model = false">
            {{$t('displayParts.cancel')}}
          </v-btn>

          <v-btn @click="confirmed(event)">
            {{$t('displayParts.ok')}}
          </v-btn>
        </template>
      </v-card>
    </v-dialog>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

    const props = defineProps({
        modelValue:{
            type: Boolean,
            required: true
        },
        title:{
            type:String,
            required: false,
            default:'Confirm'
        },
        message:{
            type: String,
            required: true,
            default: ''
        }
    })

    const emit = defineEmits(['update:modelValue','execute'])
    const model = computed({
        get: () => props.modelValue,
        set: (val) => emit('update:modelValue', val)
    })
    const confirmed = (event) => {
        emit('execute', event)
        model.value = false
    }
</script>