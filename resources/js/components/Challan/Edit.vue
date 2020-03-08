<template>
  <v-form v-model="valid" ref="challan_form">
    <v-container class="px-8 py-2">
      <v-row>
        <v-col cols="12" md="4">
          <v-text-field disabled :rules="numberRule" v-model="challan_no" label="Challan Number"></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="truck_no" label="Truck No"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="permit_no" label="Permit No"></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="driver_name" label="Driver Name"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="agent_name" label="Agent Name"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="cnic" label="Cnic"></v-text-field>
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
          <v-text-field v-model="transport" label="Transport"></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="total_amount" label="Total Amount"></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="expenses" label="Expenses"></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="grand_total" label="Grand Total"></v-text-field>
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
      <v-btn class="my-4 float-right" @click="submit_challan">Submit</v-btn>
    </v-container>
  </v-form>
</template>


<script>
export default {
  data: () => ({
    challan_no: "",
    truck_no: "",
    permit_no: "",
    driver_name: "",
    agent_name: "",
    cnic: "",
    transport: "",
    total_amount: "",
    expenses: "",
    grand_total: "",
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
        text: "Builty No",
        align: "left",
        sortable: false,
        value: "no"
      },
      { text: "Manual", value: "manual" },
      { text: "Lc/bl-no", value: "lc_bl_no" },
      { text: "Sender", value: "sender" },
      { text: "Receiver", value: "receiver" },
      { text: "Customer", value: "customer_name" },
      { text: "Payment", value: "payment_status" }
    ],
    bilties: [],
    valid: true,
    nameRule: [
      v => !!v || "Name is required",
      v => (v && v.length <= 10) || "Name must be less than 10 characters"
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
        if (!v.trim) return true;
        if (!isNaN(parseFloat(v)) && v >= 0 && v <= 99999) return true;
        return "Number has to be between 0 and 99999";
      }
    ]
  }),
  async created() {
    console.log(this.$route.params.id);
    await this.getBilties();
    this.getChallan(this.$route.params.id);
    console.log(this.bilties);
  },

  methods: {
    getChallan(id) {
      this.$http({
        url: `challan/${id}`,
        method: "GET"
      }).then(
        res => {
          console.log(res.data);
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

          for (let bilty of res.data.relationships.bilties.data) {
            console.log(bilty);
            this.selected.push(this.bilties.find(x => x.id == bilty.id));
          }
          // this.challan_no = parseInt(res.data);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    checkForBiltyNo() {
      if (!this.manual) {
        this.getChallan();
      }
    },
    getBilties() {
      this.loading = true;
      let bilties = [];
      this.$http({
        url: `bilty`,
        method: "GET"
      }).then(
        res => {
          console.log(res.data);

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
              package_total: 0,
              total_amount: 0,
              customer_name: ""
            };
            this.getCustomer(bilty.relationships.customer.data.id)
              .then(res => {
                // bilty_data.customer = res;
                bilty_data.customer_name = res.name;
                console.log(bilty_data.customer_name);
              })
              .then(bilties.push(bilty_data));
          }
          this.bilties = bilties;
          console.log(bilties);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    async getCustomer(id) {
      let customer = {};
      await this.$http({
        url: `customer/${id}`,
        method: "GET"
      }).then(
        res => {
          customer = {
            customer_no: res.data.attributes.customer_no,
            name: res.data.attributes.name,
            company: res.data.attributes.company
          };
          this.loading = false;
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return customer;
    },
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    submit_challan() {
      if (true) {
        let that = this;
        let challan = {
          challan_no: that.challan_no,
          from: that.from_city_value,
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
        console.log(challan);
        this.$http({
          url: `challan/${this.$route.params.id}`,
          data: challan,
          method: "PUT"
        }).then(
          res => {
            console.log(res);
          },
          err => {
            console.log("error status", err.response.status);
            console.log("error status text", err.response.statusText);
            // this.has_error = true
          }
        );
      }
    }
  }
};
</script>