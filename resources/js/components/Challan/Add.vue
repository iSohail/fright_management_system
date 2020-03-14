<template>
  <v-form v-model="valid" ref="challan_form">
    <v-container class="px-8 py-2">
      <v-row>
        <v-col cols="12" md="2">
          <v-text-field
            :disabled="!manual"
            :rules="numberRule"
            v-model="challan_no"
            label="Challan Number"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="2">
          <v-checkbox
            v-model="manual"
            hide-details
            class="shrink"
            label="Manual"
            @change="checkForBiltyNo()"
          ></v-checkbox>
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
          <v-text-field v-model="total_amount" readonly label="Total Amount"></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="expenses" label="Expenses"></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field v-model="grand_total" readonly label="Grand Total"></v-text-field>
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
        text: "Builty No",
        align: "left",
        value: "no"
      },
      { text: "Manual", value: "manual" },
      { text: "Lc/bl-no", value: "lc_bl_no" },
      { text: "Sender", value: "sender" },
      { text: "Receiver", value: "receiver" },
      { text: "Customer", value: "customer_name" },
      { text: "Payment", value: "payment_status" },
      { text: "Amount", value: "bilty_total" }
    ],
    bilties: [],
    valid: true,

    nameRule: [
      v => !!v || "Field is required",
      v => (v && v.length <= 30) || "Field must be less than 30 characters",
      v =>
        /(?=.*[A-Z])/.test(v) ||
        /(?=.*[a-z])/.test(v) ||
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
    total_amount() {
      let total_amount = 0;
      for (let bilty of this.bilties) {
        if (bilty.payment_status == "unpaid") {
          total_amount += parseFloat(bilty.bilty_total);
        }
      }
      return total_amount;
    },
    grand_total() {
      return parseFloat(this.total_amount) - parseFloat(this.expenses);
    }
  },
  async created() {
    this.getLastChallanNo();
    await this.getBilties();
    console.log(this.bilties);
  },

  methods: {
    getLastChallanNo() {
      this.$http({
        url: `challan/last`,
        method: "GET"
      }).then(
        res => {
          this.challan_no = parseInt(res.data);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    checkForBiltyNo() {
      if (!this.manual) {
        this.getLastChallanNo();
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
              bilty_total: bilty.attributes.bilty_total,
              customer_name: ""
            };
            if (bilty.relationships.customer.data) {
              this.getCustomer(bilty.relationships.customer.data.id).then(
                res => {
                  // bilty_data.customer = res;
                  bilty_data.customer_name = res.name;
                  console.log(bilty_data.customer_name);
                }
              );
            }
            bilties.push(bilty_data);
          }
          this.bilties = bilties;
          this.loading = false;
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
          url: `challan/create`,
          data: challan,
          method: "POST"
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