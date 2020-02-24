<template>
    <div class="text-right px-5 py-5" >
      <v-menu bottom >
        <template v-slot:activator="{ on }">
          <v-btn
            color="dark"
            dark
            v-on="on"
          >
           <v-icon>mdi-apps</v-icon>
          </v-btn>
        </template>
  
        <v-list>
          <v-list-item
            v-for="link in links" :key="link.text" router :to="link.route"
          >
            <v-list-item-title>{{ link.text }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

    </div>
</template>


<script>
  export default {
    data: () => ({
    links: [
      { text: 'Home' , route: '/'},
      { text: 'Login', route: '/login' },
      { text: 'Register', route: '/register' },
      { text: 'Admin Dashboard', route: '/admin' },
      { text: 'Dashboard', route: '/dashboard' },
    ]
  })
  }
</script>