<template>
  <div id="app">
    <v-app id="inspire">
      <Menu/>

      <v-container fill-height fluid>
        <v-row align="center" justify="center" class="mx-auto">
          <v-col class="mx-auto text-center">
            <v-card dark flat class="mx-auto" max-width="800">
              <v-card-text class="text-center font-weight-bold">
                <p class="display-2 text--primary">
                  <span
                    class="light-blue--text lighten-5 font-weight-bold"
                  >Naseeb Goods and Transport</span>
                </p>
                <div class="title">Content Management Application for handling Fright Management</div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <Footer/>
    </v-app>
  </div>
</template>

<script>
import Menu from "./MenuBar/Menu";
import Footer from "./Footer/FooterPadless";
export default {
  components: {
    Menu,
    Footer
  },
  methods: {
    changeRoute: () => {
      console.log("fsdfasd");
      this.$router.push("/login");
    }
  },
  created() {
    this.$vuetify.theme.dark = true;
  }
};
</script>