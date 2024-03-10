
<template>
  <v-app>
    <app-bar />
    <v-main>
      <router-view></router-view>
    </v-main>
    <panel-info-msg />
    <panel-warn-msg />
    <panel-error-msg />
    <loading />
  </v-app>
</template>
<script setup>
  import AppBar from '@/components/organisms/AppBar.vue'
  import PanelInfoMsg from '@/components/molecules/PanelInfoMsg.vue'
  import PanelWarnMsg from '@/components/molecules/PanelWarnMsg.vue'
  import PanelErrorMsg from '@/components/molecules/PanelErrorMsg.vue'
  import loading from '@/components/molecules/Loading.vue'

</script>

<style>
  #app{
    width: 100%;
  }
  .l-text{
    text-align: left;
  }
  .r-text{
    text-align: right;
  }
  .block-line{
    display: inline-block;
  }
  .flex-line{
    display: inline-flex;
  }
</style>
