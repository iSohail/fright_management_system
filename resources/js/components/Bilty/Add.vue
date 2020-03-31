<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>

    <v-card style="height: 100%" :loading="isUpdating" dark flat tile :disabled="isUpdating">
      <template v-slot:progress>
        <v-progress-linear absolute color="blue lighten-3" height="4" indeterminate></v-progress-linear>
      </template>
      <v-card-title class="px-8 pt-8 headline">ADD BILTY FORM</v-card-title>
      <v-form v-model="valid" ref="bilty_form">
        <v-container class="px-8 py-2">
          <v-row>
            <v-col cols="10" sm="3">
              <v-text-field
                :disabled="!manual"
                :rules="numberRule"
                v-model="bilty_no"
                dense
                filled
                label="Builty Number"
              ></v-text-field>
            </v-col>
            <v-col cols="2" sm="1">
              <v-checkbox v-model="manual" hide-details class="shrink" @change="checkForBiltyNo()"></v-checkbox>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field
                v-model="lc_bl_no"
                dense
                filled
                hint="should be unique"
                persistent-hint
                :rules="numberRule"
                label="L/C - B/L No / Container No"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
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
            <v-col cols="12" md="6">
              <v-textarea
                label="Description"
                rows="1"
                dense
                row-height="10"
                v-model="description"
                class="ma-1"
                filled
              ></v-textarea>
            </v-col>
            <v-col cols="12" sm="8" md="4">
              <v-autocomplete
                v-model="customer"
                :items="customers"
                filled
                chips
                dense
                item-text="name"
                item-value="id"
                label="Customer"
              >
                <template v-slot:selection="data">
                  <v-chip>{{ data.item.name }}</v-chip>
                </template>
                <template v-slot:item="data">
                  <template v-if="typeof data.item !== 'object'">
                    <v-list-item-content v-text="data.item"></v-list-item-content>
                  </template>
                  <template v-else>
                    <v-list-item-avatar v-html="data.item.no"></v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title v-html="data.item.name"></v-list-item-title>
                      <v-list-item-subtitle v-html="data.item.company"></v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>

              <!-- <v-divider></v-divider> -->
            </v-col>
            <v-col cols="12" sm="4" md="2">
              <v-btn block :loading="isUpdating" color="warning" @click="isUpdating = 'load'">
                <v-icon left>mdi-update</v-icon>Autofill
              </v-btn>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="from_city_value"
                :items="from_city_items"
                :rules="selectRule"
                dense
                filled
                label="From"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="to_city_value"
                :rules="selectRule"
                :items="to_city_items"
                dense
                filled
                label="To"
              ></v-autocomplete>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field v-model="sender" dense filled :rules="nameRule" label="Sender Name"></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field v-model="receiver" dense filled :rules="nameRule" label="Reciever Name"></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="12">
              <v-textarea
                label="Reciever Address"
                rows="1"
                dense
                row-height="10"
                v-model="receiver_address"
                class="ma-1"
                filled
                :rules="descriptionRule"
              ></v-textarea>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="4">
              <v-select
                v-model="payment_status_item_value"
                :items="payment_status_items"
                :rules="selectRule"
                dense
                filled
                label="Payment Status"
              ></v-select>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field
                v-model="bilty_charges"
                dense
                filled
                :rules="numberRule"
                label="Bilty Charges"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field
                v-model="local_charges"
                dense
                filled
                :rules="numberRule"
                label="Local Charges"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-data-table
            :headers="headers"
            :items="packages"
            sort-by="calories"
            dark
            class="elevation-1"
          >
            <template v-slot:top>
              <v-toolbar flat dark>
                <v-toolbar-title>Package Details</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="1000px">
                  <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
                  </template>
                  <v-card>
                    <v-card-title class="light-blue darken-3 headline">{{ formTitle }}</v-card-title>

                    <v-card-text>
                      <v-form ref="form">
                        <v-container>
                          <v-row>
                            <v-col cols="12" sm="12" md="12">
                              <v-textarea
                                label="Description"
                                rows="2"
                                row-height="20"
                                v-model="editedItem.description"
                                :rules="descriptionRule"
                              ></v-textarea>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="12" sm="8" md="4">
                              <v-text-field
                                v-model="editedItem.quantity"
                                :rules="numberRule"
                                label="Quantity"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="4">
                              <v-select
                                v-model="editedItem.unit"
                                :items="unit_items"
                                :rules="selectRule"
                                @change="unitChanged"
                                label="Unit"
                              ></v-select>
                            </v-col>
                            <v-col cols="12" sm="8" md="4">
                              <v-text-field
                                v-model="editedItem.rate"
                                :rules="numberRule"
                                label="Rate/Unit"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="3">
                              <v-text-field
                                v-model="editedItem.total_weight"
                                :rules="numberRule"
                                label="Total Weight"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="3">
                              <v-text-field
                                v-model="editedItem.total_volume"
                                :rules="numberRule"
                                label="Total Volume"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="3">
                              <v-text-field v-model="rentCalc" :rules="numberRule" label="Rent"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="8" md="3">
                              <v-text-field
                                v-model="editedItem.labour"
                                :rules="numberRule"
                                label="Labour"
                              ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-container>
                      </v-form>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                      <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-toolbar>
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn class="primary mr-2" small @click="editItem(item)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-btn class="error" small @click="deleteItem(item)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </template>
          </v-data-table>
          <v-row class="mt-5" justify="end">
            <v-col cols="12" sm="8" md="4">
              <v-text-field v-model="packages_total" dense filled readonly label="Packages Total"></v-text-field>
            </v-col>
          </v-row>
          <v-row justify="end">
            <v-col cols="12" sm="8" md="4">
              <v-text-field v-model="bilty_total" dense filled readonly label="Bilty Total"></v-text-field>
            </v-col>
          </v-row>
          <v-row justify="end">
            <v-btn class="my-4 float-right" color="success" @click="submit_bilty">Submit</v-btn>
            <v-btn
              class="my-4 ml-4 float-right"
              color="primary"
              @click="submit_print_bilty"
            >Submit & Print</v-btn>
          </v-row>
        </v-container>
      </v-form>
      <v-snackbar v-model="snackbar">
        {{ text }}
        <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-card>
  </div>
</template>


<script>
export default {
  data: () => ({
    bread_crumb_items: [
      {
        text: "Bilty"
      },
      {
        text: "Add Bilty"
      }
    ],
    snackbar: false,
    text: "",
    from_city_items: ["Karachi", "Lahore", "Sheikhupura", "Islamabad"],
    to_city_items: ["Lahore", "Sheikhupura", "Karachi", "Islamabad"],
    from_city_value: "Karachi",
    to_city_value: "Lahore",
    manual: false,
    bilty_no: "",
    date: new Date().toISOString().substr(0, 10),
    menu: false,
    description: "",
    lc_bl_no: "",
    bilty_charges: "0",
    local_charges: "0",
    sender: "",
    receiver: "",
    receiver_address: "",
    customer: [],
    isUpdating: false,
    name: "",
    customers: [],
    created_bilty: "",
    dialog: false,
    headers: [
      {
        text: "Description",
        align: "left",
        value: "description",
        class: "light-blue darken-3 white--text"
      },
      { text: "Unit", value: "unit", class: "light-blue darken-3 white--text" },
      {
        text: "Quantity",
        value: "quantity",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Weight",
        value: "total_weight",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Labour",
        value: "labour",
        class: "light-blue darken-3 white--text"
      },
      { text: "Rent", value: "rent", class: "light-blue darken-3 white--text" },
      { text: "Rate", value: "rate", class: "light-blue darken-3 white--text" },
      {
        text: "Volume",
        value: "volume",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Action",
        value: "action",
        sortable: false,
        class: "light-blue darken-3 white--text"
      }
    ],
    packages: [],
    editedIndex: -1,
    editedItem: {
      description: "",
      quantity: "0",
      unit: "",
      total_weight: "0",
      rent: "",
      labour: "0",
      rate: "",
      total_volume: "0"
    },
    defaultItem: {
      description: "",
      quantity: "0",
      unit: "",
      total_weight: "0",
      rent: "",
      labour: "0",
      rate: "",
      total_volume: "0"
    },
    payment_status_items: ["Paid", "Unpaid", "Monthly"],
    payment_status_item_value: "Unpaid",
    unit_items: ["kg", "cbm", "pck"],
    per_kg_rate: "",
    per_cbm_rate: "",
    per_pck_rate: "",
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
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    },
    rentCalc() {
      let perUnitRate = parseFloat(this.editedItem.rate);
      let totalWeight = parseFloat(this.editedItem.total_weight);
      let totalVolume = parseFloat(this.editedItem.total_volume);
      let quantity = parseFloat(this.editedItem.quantity);
      if (perUnitRate) {
        if (totalWeight && this.editedItem.unit == "kg") {
          this.editedItem.rent = perUnitRate * totalWeight;
          return this.editedItem.rent;
        }
        if (totalVolume && this.editedItem.unit == "cbm") {
          this.editedItem.rent = perUnitRate * totalVolume;
          return this.editedItem.rent;
        }
        if (quantity && this.editedItem.unit == "pck") {
          this.editedItem.rent = perUnitRate * quantity;
          return this.editedItem.rent;
        }
      }
    },
    packages_total() {
      let pck_total = 0;
      for (let pck of this.packages) {
        pck_total += parseFloat(pck.rent) + parseFloat(pck.labour);
      }
      return Math.round(pck_total);
    },
    bilty_total() {
      return Math.round(
        parseFloat(this.bilty_charges) +
          parseFloat(this.local_charges) +
          parseFloat(this.packages_total)
      );
    }
  },
  created() {
    this.getLastBiltyNo();
    this.getCustomers();
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
    isUpdating(val) {
      if (val == "load") {
        this.isUpdating = true;
        this.getCustomerDetails();
      }
    },
    customer() {
      if (!(this.customer && this.customer.length > 0)) {
        this.removeCustomerData();
      }
    }
  },

  methods: {
    unitChanged(val) {
      let perKg = parseFloat(this.per_kg_rate);
      let perCbm = parseFloat(this.per_cbm_rate);
      let perPck = parseFloat(this.per_pck_rate);
      if (val == "kg" && perKg) {
        this.editedItem.rate = perKg;
      }
      if (val == "cbm" && perCbm) {
        this.editedItem.rate = perCbm;
      }
      if (val == "pck" && perPck) {
        this.editedItem.rate = perPck;
      }
    },
    getCustomerDetails() {
      if (this.customer && this.customer.length > 0) {
        this.$http({
          url: `customer/${this.customer}`,
          method: "GET"
        }).then(
          res => {
            this.isUpdating = false;
            this.per_kg_rate = res.data.attributes.per_kg_rate;
            this.per_cbm_rate = res.data.attributes.per_cbm_rate;
            this.per_pck_rate = res.data.attributes.per_pck_rate;
            if (res.data.relationships.sender.data) {
              this.getSenderDetails(res.data.relationships.sender.data.id);
            }
            if (res.data.relationships.receiver.data) {
              this.getReceiverDetails(res.data.relationships.receiver.data.id);
            }
          },
          () => {
            this.isUpdating = false;
            this.snackbar = true;
            this.text = "Can not fetch customer details";
          }
        );
      } else {
        this.isUpdating = false;
        this.removeCustomerData();
        this.snackbar = true;
        this.text = "Please select customer first";
      }
    },
    removeCustomerData() {
      this.per_kg_rate = "";
      this.per_cbm_rate = "";
      this.per_pck_rate = "";
      this.sender = "";
      this.receiver = "";
      this.receiver_address = "";
    },
    async getSenderDetails(id) {
      await this.$http({
        url: `sender/${id}`,
        method: "GET"
      }).then(
        res => {
          this.sender = res.data.attributes.name;
        },
        () => {}
      );
    },
    async getReceiverDetails(id) {
      await this.$http({
        url: `receiver/${id}`,
        method: "GET"
      }).then(
        res => {
          this.receiver = res.data.attributes.name;
          this.receiver_address = res.data.attributes.address;
        },
        () => {}
      );
    },
    getLastBiltyNo() {
      this.$http({
        url: `bilties/last`,
        method: "GET"
      }).then(
        res => {
          this.bilty_no = parseInt(res.data);
        },
        () => {
          this.snackbar = true;
          this.text = "Can not fetch bilty no";
        }
      );
    },
    getCustomers() {
      this.$http({
        url: `customer`,
        method: "GET"
      }).then(
        res => {
          let customers = [];
          for (let customer of res.data) {
            customers.push({
              id: customer.id,
              no: customer.attributes.customer_no,
              name: customer.attributes.name,
              company: customer.attributes.company,
              cellNo: customer.attributes.cell_no,
              perKg: customer.attributes.per_kg_rate,
              perCbm: customer.attributes.per_cbm_rate,
              perPackage: customer.attributes.per_pck_rate
            });
          }
          this.customers = customers;
        },
        () => {}
      );
    },
    checkForBiltyNo() {
      if (!this.manual) {
        this.getLastBiltyNo();
      }
    },
    remove(item) {
      const index = this.customer.indexOf(item.name);
      if (index >= 0) this.customer.splice(index, 1);
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    editItem(item) {
      this.editedIndex = this.packages.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.packages.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.packages.splice(index, 1);
    },

    close() {
      this.dialog = false;
      this.resetValidation();
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
    },

    save() {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.packages[this.editedIndex], this.editedItem);
        } else {
          this.packages.push(this.editedItem);
        }
        this.close();
      }
    },
    emptyFields() {
      this.from_city_value = "Karachi";
      this.to_city_value = "Lahore";
      this.manual = false;
      this.bilty_no = "";
      this.lc_bl_no = "";
      this.bilty_charges = "0";
      this.local_charges = "0";
      this.sender = "";
      this.receiver = "";
      this.receiver_address = "";
      this.customer = [];
      this.description = "";
      this.name = "";
      this.packages = [];
      this.payment_status_item_value = "Unpaid";
      this.valid = true;
      this.$refs.bilty_form.resetValidation();
    },
    submit_bilty() {
      let clear = true;
      this.isUpdating = true;
      if (this.$refs.bilty_form.validate()) {
        let that = this;
        let bilty = {
          description: that.description,
          date: that.date,
          from: that.from_city_value,
          to: that.to_city_value,
          manual: that.manual.toString(),
          bilty_no: that.bilty_no,
          lc_bl_no: that.lc_bl_no,
          bilty_charges: that.bilty_charges,
          local_charges: that.local_charges,
          sender: that.sender,
          receiver: that.receiver,
          receiver_address: that.receiver_address,
          payment_status_item_value: that.payment_status_item_value,
          packages_total: that.packages_total,
          bilty_total: that.bilty_total,
          customer_id: that.customer,
          packages: that.packages
        };
        this.created_bilty = bilty;
        this.$http({
          url: `bilty/create`,
          data: bilty,
          method: "POST"
        }).then(
          res => {
            console.log(res);
            this.isUpdating = false;
            this.snackbar = true;
            this.text = "Successfully added bilty";
            this.removeCustomerData();
            this.emptyFields();
            this.getLastBiltyNo();
          },
          err => {
            this.isUpdating = false;
            this.snackbar = true;
            this.text =
              "Error adding bilty, Failed with Error: " +
              err.response.statusText;
            clear = false;
          }
        );
      } else {
        this.isUpdating = false;
        this.snackbar = true;
        this.text = "Validation Failed";
        clear = false;
      }
    },
    submit_print_bilty() {
      if (this.submit_bilty()) {
        this.$store.dispatch("destroyBilty");
        this.$store.dispatch("createBilty", this.created_bilty);
        let routeData = this.$router.resolve({
          name: "invoice.bilty",
          query: { id: this.bilty_no, manual: this.manual }
        });
        window.open(routeData.href, "_blank");
      }
    }
  }
};
</script>