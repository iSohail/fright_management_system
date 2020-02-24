<template>
  <div id="app">
  <v-app id="inspire">

    <Menu/>


<v-container fill-height fluid>
  <v-row align="center"
      justify="center"
      class="mx-auto">
      <v-col class="mx-auto text-center">
<v-card
light
flat
    class=" mx-auto"
    max-width="800"
  >
    <v-card-text class="text-center font-weight-bold ">
      <p class="display-2 text--primary " >
        <span class="  accent-2  green--text font-weight-black">Naseeb Goods and Transport</span>
      </p>
      <div class="title">
        Content Management Application for handling Fright Management
      </div>
    </v-card-text>
  </v-card>




      </v-col>
  </v-row>
</v-container>

<v-footer padless>
    <v-col
      class="text-center"
      cols="12"
    >
      {{ new Date().getFullYear() }} — <strong>Logical4IO</strong>
    </v-col>
  </v-footer>





  </v-app>
</div>
</template>

<script>
import Menu from './MenuBar/Menu'
  export default {
  components:{
    Menu
  },
  methods:{
    
    changeRoute: () =>{
      console.log('fsdfasd')
      this.$router.push('/login')
    }
  }
  }
</script>