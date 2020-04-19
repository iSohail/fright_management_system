<template>
  <v-app id="inspire" dark>
    <Menu/>
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center" class="mx-auto">
          <v-col cols="12" sm="8" md="4" class="mx-auto">
            <v-card class="elevation-12 mx-auto">
              <v-progress-linear
                :active="loading"
                color="indigo darken-4"
                striped
                top
                :indeterminate="loading"
                absolute
              ></v-progress-linear>
              <v-toolbar color="primary" dark flat max-height="150px">
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer/>
              </v-toolbar>
              <v-card-text>
                <!-- <v-progress-linear value="15"></v-progress-linear> -->
                <v-form @keyup.native.enter="login">
                  <v-text-field label="Email" name="email" v-model="email" type="text"/>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show2 ? 'text' : 'password'"
                    v-model="password"
                    @click:append="show2 = !show2"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer/>
                <v-btn class="mr-3 mb-3" color="primary" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
        <v-snackbar v-model="snackbar">
          {{ text }}
          <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
        </v-snackbar>
      </v-container>
      <Footer/>
    </v-content>
  </v-app>
</template>

<script>
import Menu from "../MenuBar/Menu";
import Footer from "../Footer/FooterPadless";
export default {
  data() {
    return {
      show2: false,
      snackbar: false,
      text: "",
      email: null,
      password: null,
      has_error: false,
      loading: false
    };
  },
  mounted() {
    //
  },
  components: {
    Menu,
    Footer
  },
  methods: {
    login() {
      // get the redirect object
      this.loading = true;
      var redirect = this.$auth.redirect();
      var app = this;
      this.$auth.login({
        params: {
          email: app.email,
          password: app.password
        },
        success: function() {
          // handle redirection
          const redirectTo = redirect
            ? redirect.from.name
            : this.$auth.user().role === 2
            ? "admin.dashboard.stats"
            : "dashboard";
          this.$router.push({ name: redirectTo });
        },
        error: function() {
          console.log("eerk");
          this.loading = false;
          app.has_error = true;
          this.snackbar = true;
          this.text = "error loging in, check credentials";
        },
        // rememberMe: true,
        fetchUser: true
      });
    }
  },
  created() {
    this.$vuetify.theme.dark = true;
  }
};
</script>