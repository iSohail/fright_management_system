<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>

    <v-card
      style="height: 100%; min-height: 100vh"
      :loading="isUpdating"
      dark
      flat
      tile
      :disabled="isUpdating"
    >
      <template v-slot:progress>
        <v-progress-linear absolute color="blue lighten-3" height="4" indeterminate></v-progress-linear>
      </template>
      <v-card-title class="px-8 pt-8 headline">ADD CHALLAN FORM</v-card-title>
      <v-card-text>
        <v-form v-model="valid" ref="challan_form">
          <v-container class="px-4 py-2">
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  disabled
                  dense
                  filled
                  :rules="numberRule"
                  v-model="challan_no"
                  label="Challan Number"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="selectRule"
                  v-model="truck_no"
                  label="Truck No"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="selectRule"
                  v-model="permit_no"
                  label="Permit No"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="3">
                <v-menu
                  ref="menu"
                  v-model="menu"
                  :close-on-content-click="false"
                  :return-value.sync="date"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field v-model="date" label="Date" readonly dense filled v-on="on"></v-text-field>
                  </template>
                  <v-date-picker v-model="date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                    <v-btn text color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="nameRule"
                  v-model="driver_name"
                  label="Driver Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="nameRule"
                  v-model="agent_name"
                  label="Agent Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="selectRule"
                  v-model="cnic"
                  label="Cnic"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="from_city_value"
                  :items="from_city_items"
                  :rules="selectRule"
                  dense
                  filled
                  label="From"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="to_city_value"
                  :rules="selectRule"
                  :items="to_city_items"
                  dense
                  filled
                  label="To"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  clearable
                  :rules="selectRule"
                  v-model="transport"
                  label="Transport"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  :rules="selectRule"
                  v-model="total_amount"
                  readonly
                  label="Total Amount"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field dense filled :rules="selectRule" v-model="expenses" label="Expenses"></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  dense
                  filled
                  :rules="selectRule"
                  v-model="grand_total"
                  readonly
                  label="Grand Total"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-spacer></v-spacer>
            <v-card>
              <v-card-title>
                Bilties
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
              </v-card-title>
              <v-data-table
                v-model="selected"
                :headers="headers"
                :items="bilties"
                :search="search"
                item-key="id"
                show-select
                :loading="loading"
                loading-text="Loading... Please wait"
                class="elevation-1"
              ></v-data-table>
            </v-card>
            <v-row justify="end">
              <v-btn class="my-4 success mx-4" @click="submit_challan">Submit</v-btn>
            </v-row>
          </v-container>
        </v-form>
      </v-card-text>
    </v-card>
    <v-snackbar v-model="snackbar">
      {{ text }}
      <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data: () => ({
    bread_crumb_items: [
      {
        text: "Challan"
      },
      {
        text: "Add Challan"
      }
    ],
    snackbar: false,
    text: "",
    isUpdating: false,
    menu: false,
    date: new Date().toISOString().substr(0, 10),
    challan_no: "",
    truck_no: "-",
    permit_no: "-",
    driver_name: "-",
    agent_name: "-",
    cnic: "-",
    transport: "-",
    expenses: "0",
    search: "",
    loading: true,
    from_city_items: ["Karachi", "Lahore", "Sheikhupura", "Islamabad"],
    to_city_items: ["Lahore", "Sheikhupura", "Karachi", "Islamabad"],
    from_city_value: "Karachi",
    to_city_value: "Lahore",
    manual: false,
    selected: [],
    headers: [
      {
        text: "",
        value: "data-table-select",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Builty No",
        align: "left",
        value: "no",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Manual",
        value: "manual",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Lc/bl-no",
        value: "lc_bl_no",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Sender",
        value: "sender",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Receiver",
        value: "receiver",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Customer",
        value: "customer_name",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Payment",
        value: "payment_status",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Amount",
        value: "bilty_total",
        class: "light-blue darken-3 white--text"
      }
    ],
    bilties: [],
    valid: true,
    nameRule: [
      v => !!v || "Field is required",
      v => (v && v.length <= 30) || "Field must be less than 30 characters",
      v =>
        /(?=.*[A-Z])/.test(v) ||
        /(?=.*[a-z])/.test(v) ||
        /(?=.*[-])/.test(v) ||
        "Only characters allowed"
    ],
    selectRule: [v => !!v || "Field is required"],
    descriptionRule: [
      v => !!v || "Description is required",
      v =>
        (v && v.length <= 1000) ||
        "Descriptipn must be less than 1000 characters"
    ],
    numberRule: [
      v => !!v || "Field is required",
      v => {
        if (!isNaN(parseFloat(v)) && v >= 0 && v <= 9999999) return true;
        return "Only numbers allowed";
      }
    ]
  }),
  computed: {
    total_amount: {
      get() {
        let total_amount = 0;
        for (let bilty of this.selected) {
          if (bilty.payment_status == "unpaid") {
            total_amount += parseFloat(bilty.bilty_total);
          }
        }
        return total_amount;
      },
      set(newName) {
        return newName;
      }
    },
    grand_total: {
      get() {
        return parseFloat(this.total_amount) - parseFloat(this.expenses);
      },
      set(newName) {
        return newName;
      }
    }
  },
  async created() {
    this.isUpdating = true;
    await this.getChallan(this.$route.params.id);
    await this.getChallanBilties(this.$route.params.id);
    await this.getBilties();
    this.isUpdating = false;
  },

  methods: {
    async getChallan(id) {
      await this.$http({
        url: `challan/${id}`,
        method: "GET"
      }).then(
        res => {
          this.truck_no = res.data.attributes.truck_no;
          this.permit_no = res.data.attributes.permit_no;
          this.challan_no = res.data.attributes.challan_no;
          this.from_city_value = res.data.attributes.from;
          this.to_city_value = res.data.attributes.to;
          this.transport = res.data.attributes.transport;
          this.driver_name = res.data.attributes.driver_name;
          this.agent_name = res.data.attributes.agent_name;
          this.cnic = res.data.attributes.cnic;
          this.total_amount = res.data.attributes.total_amount;
          this.expenses = res.data.attributes.expenses;
          this.grand_total = res.data.attributes.grand_total;
        },
        () => {
          this.isUpdating = false;
          this.snackbar = true;
          this.text = "Can not fetch challan details";
        }
      );
    },
    async getChallanBilties(challan_id) {
      this.loading = true;
      let bilties = [];
      await this.$http({
        url: `bilties/challan/${challan_id}`,
        method: "GET"
      }).then(
        res => {
          for (let bilty of res.data) {
            let bilty_data = {
              id: bilty.id,
              no: bilty.attributes.bilty_no,
              lc_bl_no: bilty.attributes.lg_bl_no,
              manual: bilty.attributes.manual,
              from: bilty.attributes.from,
              to: bilty.attributes.to,
              sender: bilty.attributes.sender,
              receiver: bilty.attributes.receiver,
              receiver_address: bilty.attributes.receiver_address,
              status: bilty.attributes.status,
              payment_status: bilty.attributes.payment_status,
              bilty_charges: bilty.attributes.bilty_charges,
              local_charges: bilty.attributes.local_charges,
              bilty_total: bilty.attributes.bilty_total
            };
            bilties.push(bilty_data);
          }
          this.bilties = bilties;
          this.selected = bilties;
          this.loading = false;
        },
        () => {
          this.loading = false;
          this.isUpdating = false;
          this.snackbar = true;
          this.text = "Can not fetch bilties";
        }
      );
    },
    async getBilties() {
      this.loading = true;
      let bilties = [];
      await this.$http({
        url: `bilties/registered`,
        method: "GET"
      }).then(
        res => {
          for (let bilty of res.data) {
            let bilty_data = {
              id: bilty.id,
              no: bilty.attributes.bilty_no,
              lc_bl_no: bilty.attributes.lg_bl_no,
              manual: bilty.attributes.manual,
              from: bilty.attributes.from,
              to: bilty.attributes.to,
              sender: bilty.attributes.sender,
              receiver: bilty.attributes.receiver,
              receiver_address: bilty.attributes.receiver_address,
              status: bilty.attributes.status,
              payment_status: bilty.attributes.payment_status,
              bilty_charges: bilty.attributes.bilty_charges,
              local_charges: bilty.attributes.local_charges,
              bilty_total: bilty.attributes.bilty_total
            };
            this.bilties.push(bilty_data);
          }
          this.loading = false;
        },
        () => {
          this.loading = false;
          this.isUpdating = false;
          this.snackbar = true;
          this.text = "Can not fetch bilties";
        }
      );
    },
    emptyFields() {
      this.truck_no = "-";
      this.permit_no = "-";
      this.driver_name = "-";
      this.agent_name = "-";
      this.cnic = "-";
      this.transport = "-";
      this.expenses = "0";
      this.from_city_items = ["Karachi", "Lahore", "Sheikhupura", "Islamabad"];
      this.to_city_items = ["Lahore", "Sheikhupura", "Karachi", "Islamabad"];
      this.from_city_value = "Karachi";
      this.to_city_value = "Lahore";
      this.selected = [];
    },
    submit_challan() {
      let check = true;
      this.isUpdating = true;
      if (!this.selected.length > 0) {
        this.isUpdating = false;
        this.snackbar = true;
        this.text = "Select bilties first";
        check = false;
      }
      if (this.$refs.challan_form.validate()) {
        let that = this;
        let challan = {
          challan_no: that.challan_no,
          from: that.from_city_value,
          date: that.date,
          to: that.to_city_value,
          manual: that.manual.toString(),
          truck_no: that.truck_no,
          permit_no: that.permit_no,
          driver_name: that.driver_name,
          agent_name: that.agent_name,
          cnic: that.cnic,
          transport: that.transport,
          total_amount: that.total_amount,
          expenses: that.expenses,
          grand_total: that.grand_total,
          bilties: []
        };

        for (let bilty of this.selected) {
          challan.bilties.push(bilty.id);
        }
        this.$http({
          url: `challan/${this.$route.params.id}`,
          data: challan,
          method: "PUT"
        }).then(
          res => {
            console.log(res);
            this.isUpdating = false;
            this.snackbar = true;
            this.text = "Successfully updated challan";
            let user = this.$auth.user();
            if (user.role == 2) {
              this.$router.push({ path: `/admin/challan/manage` });
            } else {
              this.$router.push({ path: `/operator/challan/manage` });
            }
          },
          err => {
            check = false;
            this.isUpdating = false;
            this.snackbar = true;
            this.text =
              "Error adding challan, Failed with Error: " +
              err.response.statusText;
          }
        );
      } else {
        this.isUpdating = false;
        this.snackbar = true;
        this.text = "Validation Failed";
        check = false;
      }
      return check;
    }
  }
};
</script>