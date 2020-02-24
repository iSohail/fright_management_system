<template>
  <v-app id="inspire">
    <Menu />
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer/>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Email"
                    name="email"
                    prepend-icon="person"
                    v-model="email"
                    type="text"
                  />

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="lock"
                    type="password"
                    v-model="password"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer/>
                <v-btn color="primary" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import Menu from '../MenuBar/Menu'
export default {
  
  data() {
    return {
      email: null,
      password: null,
      has_error: false
    };
  },
  mounted() {
    //
  },
  components:{
    Menu
  },
  methods: {
    login() {
      console.log(this.email, " ", this.password);
      // get the redirect object
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
            ? "admin.dashboard"
            : "dashboard";
          console.log(redirectTo, "HeLLO ROUTER");
          this.$router.push({ name: redirectTo });
        },
        error: function() {
          console.log("errorrr");
          app.has_error = true;
        },
        rememberMe: true,
        fetchUser: true
      });
    }
  }
};
</script>