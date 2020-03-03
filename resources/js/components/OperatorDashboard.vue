<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app clipped>
      <v-list>
        <v-list-item router :to="dashboard.route">
          <v-list-item-action>
            <v-icon>{{dashboard.icon}}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{dashboard.text}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group prepend-icon>
          <template v-slot:activator>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Bilty</v-list-item-title>
          </template>

          <v-list-item v-for="(bilty, i) in bilty" :key="i" router :to="bilty.link">
            <v-list-item-title class="pl-4" v-text="bilty.title"></v-list-item-title>
            <v-list-item-icon>
              <!-- <v-icon>mdi-home</v-icon> -->
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-group prepend-icon>
          <template v-slot:activator>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Customer</v-list-item-title>
          </template>

          <v-list-item v-for="(customer, i) in customers" :key="i" router :to="customer.link">
            <v-list-item-title class="pl-4" v-text="customer.title"></v-list-item-title>
            <v-list-item-icon>
              <!-- <v-icon v-text="bilty[1]"></v-icon> -->
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-group prepend-icon>
          <template v-slot:activator>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Accounts</v-list-item-title>
          </template>

          <v-list-item v-for="(account, i) in accounts" :key="i" router :to="account.link">
            <v-list-item-title class="pl-4" v-text="account.title"></v-list-item-title>
            <v-list-item-icon>
              <!-- <v-icon v-text="bilty[1]"></v-icon> -->
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>

        <v-list-group prepend-icon>
          <template v-slot:activator>
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Reports</v-list-item-title>
          </template>

          <v-list-item v-for="(report, i) in reports" :key="i" router :to="report.link">
            <v-list-item-title class="pl-4" v-text="report.title"></v-list-item-title>
            <v-list-item-icon>
              <!-- <v-icon v-text="bilty[1]"></v-icon> -->
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
      <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>

    <v-content>
      <router-view></router-view>
    </v-content>

    <v-footer app>
      <span>&copy; 2019 Admin</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  props: {
    source: String
  },

  data: () => ({
    drawer: null,
    dashboard: {
      icon: "mdi-view-dashboard",
      text: "Dashboard",
      route: "/dashboard"
    },
    bilty: [
      { title: "Add Bilty", link: "/dashboard/bilty/create" },
      { title: "Manage Bilty", link: "/dashboard/bilty/manage" },
      { title: "Manage Packages", link: "/dashboard/package" }
    ],
    customers: [
      { title: "Add Customer", link: "/dashboard/customer/create" },
      { title: "Manage Customer", link: "/dashboard/customer/manage" }
    ],
    accounts: [{ title: "Customer Account", link: "/dashboard/account/customer" }],
    reports: [
      { title: "Bilty Report", link: "/dashboard/report/bilty" },
      { title: "Challan Report", link: "/dashboard/report/challan" }
    ]
  }),

  created() {
    this.$vuetify.theme.dark = true;
  }
};
</script>