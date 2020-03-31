<template>
  <v-container>
    <v-progress-linear
      :active="loading"
      :indeterminate="loading"
      absolute
      top
      color="blue lighten-3"
    ></v-progress-linear>
    <v-row>
      <v-col cols="12" sm="6" md="3">
        <v-card class="mt-4 mx-auto" max-width="400">
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="red darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <v-icon x-large class="my-4">mdi-truck-fast</v-icon>
          </v-sheet>

          <v-card-text class="pt-0 text-center text-margin">
            <div class="title font-weight-light">Bilties</div>
            <div class="subtitle-1 font-weight-light grey--text">{{total_bilties}}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card class="mt-4 mx-auto" max-width="400">
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="purple darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <v-icon x-large class="my-4">mdi-clipboard-check</v-icon>
          </v-sheet>

          <v-card-text class="pt-0 text-center text-margin">
            <div class="title font-weight-light">Challans</div>
            <div class="subtitle-1 font-weight-light grey--text">{{total_challans}}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card class="mt-4 mx-auto" max-width="400">
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="green darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <v-icon x-large class="my-4">mdi-account-group</v-icon>
          </v-sheet>

          <v-card-text class="pt-0 text-center text-margin">
            <div class="title font-weight-light">Customers</div>
            <div class="subtitle-1 font-weight-light grey--text">{{total_customers}}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card class="mt-4 mx-auto" max-width="400">
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="orange darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <v-icon x-large class="my-4">mdi-cash-multiple</v-icon>
          </v-sheet>

          <v-card-text class="pt-0 text-center text-margin">
            <div class="title font-weight-light">Income</div>
            <div class="subtitle-1 font-weight-light grey--text">{{amount_paid}}</div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="mt-5">
      <v-col cols="12" md="6">
        <v-card>
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="light-blue darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <p class="title py-4">Pending Invoices</p>
          </v-sheet>
          <simplebar style="height: 400px" data-simplebar-auto-hide="false">
            <v-simple-table fixed-header>
              <template v-slot:default>
                <thead>
                  <tr class>
                    <th class="light-blue darken-3 white--text text-left">No</th>
                    <th class="light-blue darken-3 white--text text-left">description</th>
                    <th class="light-blue darken-3 white--text text-left">A/R</th>
                  </tr>
                </thead>
                <tbody>
                  <div v-if="pending_ledgers > 0">
                    <tr v-for="item in pending_ledgers" :key="item.name">
                      <td>{{ item.ledger_no }}</td>
                      <td>{{ item.description }}</td>
                      <td>
                        <v-chip
                          class="ma-2"
                          :color="pendingColor(item.account_receivable)"
                          text-color="white"
                        >{{ item.account_receivable }}</v-chip>
                      </td>
                    </tr>
                  </div>
                  <div v-else class="text-center" style="width: 100%">No data to show</div>
                </tbody>
              </template>
            </v-simple-table>
          </simplebar>
        </v-card>
      </v-col>
      <v-col cols="12" md="6">
        <v-card>
          <v-sheet
            class="v-sheet--offset mx-auto text-center"
            color="light-blue darken-3"
            elevation="12"
            max-width="calc(100% - 140px)"
          >
            <p class="title py-4">Top 10 Customers</p>
          </v-sheet>
          <simplebar style="height: 400px" data-simplebar-auto-hide="false">
            <v-simple-table fixed-header>
              <template v-slot:default>
                <thead>
                  <tr class>
                    <th class="light-blue darken-3 white--text text-left">No</th>
                    <th class="light-blue darken-3 white--text text-left">Name</th>
                    <th class="light-blue darken-3 white--text text-left">Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  <div v-if="pending_ledgers > 0">
                    <tr v-for="item in top_customers" :key="item.name">
                      <td>{{ item.customer_no }}</td>
                      <td>{{ item.name }}</td>
                      <td>
                        <v-chip
                          class="ma-2"
                          :color="revenueColor(item.revenue)"
                          text-color="white"
                        >{{ item.revenue }}</v-chip>
                      </td>
                    </tr>
                  </div>
                  <div v-else class="text-center" style="width: 100%">No data to show</div>
                </tbody>
              </template>
            </v-simple-table>
          </simplebar>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" class="mx-auto">
        <D3BarChart :config="chart_config" :datum="chart_data" :title="chart_title"></D3BarChart>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { D3BarChart } from "vue-d3-charts";
import simplebar from "simplebar-vue";
import "simplebar/dist/simplebar.min.css";
export default {
  data: () => ({
    // data
    loading: false,
    total_customers: 0,
    total_bilties: 0,
    total_challans: 0,
    amount_paid: 0,
    chart_title: "Bilties in a month",
    chart_data: [],
    chart_config: {
      key: "month",
      // currentKey: this.getCurrentMonth(),
      values: ["bilties"],
      axis: {
        yTicks: 5
      },
      color: {
        default: "#2979FF",
        current: "#E3F2FD"
      },
      margin: {
        top: 20,
        right: 100,
        bottom: 20,
        left: 40
      }
    },
    pending_ledgers: [],
    top_customers: []
  }),
  components: {
    simplebar,
    D3BarChart
  },
  created() {
    this.initiate();
  },
  methods: {
    revenueColor(revenue) {
      console.log(revenue);
      if (revenue > 100000) {
        return "red darken-3";
      }
      if (revenue > 50000) {
        return "orange darken-3";
      }
      return "blue darken-3";
    },
    pendingColor(revenue) {
      console.log(revenue);
      if (revenue > 10000) {
        return "red darken-3";
      }
      if (revenue > 5000) {
        return "orange darken-3";
      }
      return "blue darken-3";
    },
    async initiate() {
      this.loading = true;
      await this.getTotalBilties();
      await this.getTotalChallans();
      await this.getTotalCustomers();
      await this.getTotalIncome();
      await this.getPendingLedger();
      await this.getTopCustomer();
      await this.getChartData();
      await this.getCurrentMonth();
      this.loading = false;
    },
    async getTotalBilties() {
      await this.$http({
        url: `bilties/total`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          this.total_bilties = res.data.total_bilties;
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getTotalChallans() {
      await this.$http({
        url: `challans/total`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          this.total_challans = res.data.total_challans;
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getTotalCustomers() {
      await this.$http({
        url: `customers/total`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          this.total_customers = res.data.total_customers;
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getTotalIncome() {
      await this.$http({
        url: `ledger/paid/amount`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          this.amount_paid = res.data.amount_paid;
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getPendingLedger() {
      await this.$http({
        url: `ledgers/pending/amount`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          let pending_ledgers = [];
          for (let ledger of res.data) {
            let ledger_data = {
              id: ledger.id,
              ledger_no: ledger.attributes.ledger_no,
              description: ledger.attributes.description,
              account_receivable: ledger.attributes.account_receivable
            };
            pending_ledgers.push(ledger_data);
          }
          this.pending_ledgers = pending_ledgers;
        },
        err => {
          console.log(err);
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getTopCustomer() {
      await this.$http({
        url: `customers/top`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          let top_customers = [];
          for (let customer of res.data) {
            let match = top_customers.find(x => x.id == customer.id);
            if (match) {
              match.revenue += parseFloat(customer.amount_paid);
              continue;
            }
            let customer_data = {
              id: customer.id,
              customer_no: customer.customer_no,
              name: customer.name,
              revenue: parseFloat(customer.amount_paid)
            };
            top_customers.push(customer_data);
          }
          top_customers.sort(function(obj1, obj2) {
            // Ascending: first age less than the previous
            return obj2.revenue - obj1.revenue;
          });
          this.top_customers = top_customers.slice(0, 10);
          console.log(top_customers, "top customers");
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    async getChartData() {
      await this.$http({
        url: `bilties/total/month`,
        method: "GET"
      }).then(
        res => {
          console.log(res);

          let jan = 0,
            feb = 0,
            mar = 0,
            apr = 0,
            may = 0,
            jun = 0,
            jul = 0,
            aug = 0,
            sep = 0,
            oct = 0,
            nov = 0,
            dec = 0;
          for (let item of res.data) {
            item = item.split(" ", 1);
            let biltyDate = new Date(item);
            if (biltyDate.getMonth() == 0) {
              jan += 1;
            } else if (biltyDate.getMonth() == 1) {
              feb += 1;
            } else if (biltyDate.getMonth() == 2) {
              mar += 1;
            } else if (biltyDate.getMonth() == 3) {
              apr += 1;
            } else if (biltyDate.getMonth() == 4) {
              may += 1;
            } else if (biltyDate.getMonth() == 5) {
              jun += 1;
            } else if (biltyDate.getMonth() == 6) {
              jul += 1;
            } else if (biltyDate.getMonth() == 7) {
              aug += 1;
            } else if (biltyDate.getMonth() == 8) {
              sep += 1;
            } else if (biltyDate.getMonth() == 9) {
              oct += 1;
            } else if (biltyDate.getMonth() == 10) {
              nov += 1;
            } else if (biltyDate.getMonth() == 11) {
              dec += 1;
            }
          }
          console.log(jan);
          this.chart_data = [
            { bilties: jan, month: "JAN" },
            { bilties: feb, month: "FEB" },
            { bilties: mar, month: "MAR" },
            { bilties: apr, month: "APR" },
            { bilties: may, month: "MAY" },
            { bilties: jun, month: "JUN" },
            { bilties: jul, month: "JUL" },
            { bilties: aug, month: "AUG" },
            { bilties: sep, month: "SEP" },
            { bilties: oct, month: "OCT" },
            { bilties: nov, month: "NOV" },
            { bilties: dec, month: "DEC" }
          ];
        },
        err => {
          // this.snackbar = true;
          // this.text = "Error: " + err.response.statusText;
        }
      );
    },
    getCurrentMonth() {
      let date = new Date();
      if (date.getMonth() == 0) {
        this.chart_config.currentKey = "JAN";
      } else if (date.getMonth() == 1) {
        this.chart_config.currentKey = "FEB";
      } else if (date.getMonth() == 2) {
        this.chart_config.currentKey = "MAR";
      } else if (date.getMonth() == 3) {
        this.chart_config.currentKey = "APR";
      } else if (date.getMonth() == 4) {
        this.chart_config.currentKey = "MAY";
      } else if (date.getMonth() == 5) {
        this.chart_config.currentKey = "JUN";
      } else if (date.getMonth() == 6) {
        this.chart_config.currentKey = "JUL";
      } else if (date.getMonth() == 7) {
        this.chart_config.currentKey = "AUG";
      } else if (date.getMonth() == 8) {
        this.chart_config.currentKey = "SEP";
      } else if (date.getMonth() == 9) {
        this.chart_config.currentKey = "OCT";
      } else if (date.getMonth() == 10) {
        this.chart_config.currentKey = "NOV";
      } else if (date.getMonth() == 11) {
        this.chart_config.currentKey = "DEC";
      }
    }
  }
};
</script>

<style scoped>
.v-sheet--offset {
  top: -24px;
  position: relative;
}
.text-margin {
  margin-top: -10px;
}
</style>