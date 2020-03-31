<template>
  <div id="app">
    <v-app id="inspire">
      <v-container class="my-5 fill-height">
        <v-row class="mt-n12" justify="center">
          <Menu/>
          <v-col class="text-center" cols="auto">
            <v-img
              class="mb-12"
              contain
              height="256"
              src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-light.png"
            />
            <h1 class="display-4 font-weight-bold mb-6">Oops! 404</h1>

            <p class="mb-12 display-1 grey--text text--darken-2">There is no page here</p>

            <v-btn color="primary" x-large to="/">
              Home page
              <v-icon right>mdi-open-in-new</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <Footer/>
      </v-container>
    </v-app>
  </div>
</template>

<script>
import Menu from "../MenuBar/Menu";
import Footer from "../Footer/FooterPadless";

export default {
  name: "FourOhFour",
  components: {
    Menu,
    Footer
  },
  created() {
    this.$vuetify.theme.dark = true;
  }
};
</script>