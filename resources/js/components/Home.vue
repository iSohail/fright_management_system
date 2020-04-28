<template>
  <div id="app">
    <v-app id="inspire">
      <Menu/>

      <v-container fill-height fluid>
        <v-row align="center" justify="center" class="mx-auto">
          <v-col class="mx-auto text-center">
            <v-card dark flat class="mx-auto" max-width="800">
              <v-card-text class="text-center font-weight-bold">
                <img :src="source" style="width:150px;" alt="logo">
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
  data: () => ({
    source: ""
  }),
  components: {
    Menu,
    Footer
  },
  methods: {
    changeRoute: () => {
      this.$router.push("/login");
    }
  },
  created() {
    this.source = `${process.env.MIX_APP_URL}/storage/logo.png`;
    this.$vuetify.theme.dark = true;
  }
};
</script>