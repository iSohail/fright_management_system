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
                    type="password"
                    v-model="password"
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
      // axios.post('/api/login' , {'email': this.email , 'password' : this.password})

      console.log(this.email, " ", this.password);
      // get the redirect object
      this.loading = true;
      var redirect = this.$auth.redirect();
      console.log(redirect);
      var app = this;
      this.$auth.login({
        params: {
          email: app.email,
          password: app.password
        },
        success: function() {
          // handle redirection
          console.log(redirect, "success");
          const redirectTo = redirect
            ? redirect.from.name
            : this.$auth.user().role === 2
            ? "admin.dashboard.stats"
            : "dashboard";
          console.log(redirectTo, "HeLLO ROUTER");
          this.$router.push({ name: redirectTo });
        },
        error: function() {
          this.loading = false;
          console.log("errorrr");
          app.has_error = true;
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