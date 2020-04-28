<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app clipped>
      <v-list height="100%">
        <div style="height: calc(100% - 50px); overflow:auto; width: 100%">
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
                <v-icon>mdi-truck-fast</v-icon>
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
                <v-icon>mdi-clipboard-check</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Challan</v-list-item-title>
            </template>

            <v-list-item v-for="(challan, i) in challan" :key="i" router :to="challan.link">
              <v-list-item-title class="pl-4" v-text="challan.title"></v-list-item-title>
              <v-list-item-icon>
                <!-- <v-icon>mdi-home</v-icon> -->
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>

          <v-list-group prepend-icon>
            <template v-slot:activator>
              <v-list-item-icon>
                <v-icon>mdi-account-group</v-icon>
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
                <v-icon>mdi-cash-multiple</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Ledger</v-list-item-title>
            </template>

            <v-list-item v-for="(ledger, i) in ledgers" :key="i" router :to="ledger.link">
              <v-list-item-title class="pl-4" v-text="ledger.title"></v-list-item-title>
              <v-list-item-icon>
                <!-- <v-icon v-text="bilty[1]"></v-icon> -->
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>

          <v-list-group prepend-icon>
            <template v-slot:activator>
              <v-list-item-icon>
                <v-icon>mdi-account-cash</v-icon>
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

          <!-- <v-list-group prepend-icon>
            <template v-slot:activator>
              <v-list-item-icon>
                <v-icon>mdi-clipboard-file</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Reports</v-list-item-title>
            </template>

            <v-list-item v-for="(report, i) in reports" :key="i" router :to="report.link">
              <v-list-item-title class="pl-4" v-text="report.title"></v-list-item-title>
              <v-list-item-icon>
                <v-icon v-text="bilty[1]"></v-icon>
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>-->
        </div>

        <div style="position: absolute; bottom:0px; width: 100%">
          <v-list-item class="light-blue darken-3" @click="logout">
            <v-list-item-action>
              <v-icon>mdi-export</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Logout</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </div>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
      <v-toolbar-title v-if="windowWidth > 720" class="mx-auto">BILTY MANAGEMENT SYSTEM</v-toolbar-title>
      <v-toolbar-title v-else class="mx-auto">BMS</v-toolbar-title>
      <div class="mr-2">
        <p
          class="caption grey--text"
          style="padding: 0px; margin: 0px"
        >{{ $auth.user().name.toUpperCase()}} ( {{ $auth.user().role == '2' ? 'admin' : ($auth.user().role == '1' ? 'operator' : ( $auth.user().role == '3' ? 'sender' : ( $auth.user().role == '4' ? 'receiver' : 'none' ) ) ) }} )</p>
        <p class="caption grey--text" style="padding: 0px; margin: 0px">{{ $auth.user().email}}</p>
      </div>
      <!-- <p>email</p> -->
      <v-btn @click="logout" icon>
        <v-icon>mdi-export</v-icon>
      </v-btn>
    </v-app-bar>

    <v-content>
      <router-view></router-view>
    </v-content>

    <v-footer app>
      <span class="mx-auto">Logical4io</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  props: {
    source: String
  },

  data: () => ({
    windowWidth: window.innerWidth,
    drawer: null,
    dashboard: {
      icon: "mdi-view-dashboard",
      text: "Dashboard",
      route: "/operator"
    },
    bilty: [
      { title: "Add Bilty", link: "/operator/bilty/create" },
      { title: "Manage Bilty", link: "/operator/bilty/manage" },
      { title: "Receive Bilty", link: "/operator/bilty/receive" }
    ],
    challan: [
      { title: "Add challan", link: "/operator/challan/create" },
      { title: "Manage challan", link: "/operator/challan/manage" }
      // ?{ title: "Manage Packages", link: "/operator/package" }
    ],
    customers: [
      { title: "Add Customer", link: "/operator/customer/create" },
      { title: "Manage Customer", link: "/operator/customer/manage" },
      { title: "Sender", link: "/operator/customer/sender" },
      { title: "Receiver", link: "/operator/customer/receiver" }
    ],
    ledgers: [
      { title: "Add Ledger", link: "/operator/bilty/monthly" },
      { title: "Manage Ledger", link: "/operator/ledger/manage" }
    ],
    accounts: [
      { title: "Customer Account", link: "/operator/account/customer" }
    ],
    reports: [
      { title: "Bilty Report", link: "/operator/report/bilty" },
      { title: "Challan Report", link: "/operator/report/challan" },
      { title: "Monthly Billing", link: "/operator/bilty/monthly" }
    ]
  }),

  created() {
    this.$vuetify.theme.dark = true;
  },
  mounted() {
    window.onresize = () => {
      this.windowWidth = window.innerWidth;
    };
  },
  methods: {
    logout() {
      localStorage.clear();
      this.$router.push({ path: `/login` });
    }
  }
};
</script>