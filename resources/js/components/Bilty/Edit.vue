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
      <v-card-title class="px-8 pt-8 headline">EDIT BILTY FORM</v-card-title>
      <v-form v-model="valid" ref="bilty_form">
        <v-container class="px-8 py-2">
          <v-row>
            <v-col cols="10" sm="3">
              <v-text-field
                disabled
                :rules="numberRule"
                v-model="bilty_no"
                dense
                filled
                label="Builty Number"
              ></v-text-field>
            </v-col>
            <v-col cols="2" sm="1">
              <v-checkbox v-model="manual" disabled hide-details class="shrink"></v-checkbox>
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
            <v-col class="d-flex" cols="6" md="3">
              <v-select
                v-model="status_item_value"
                :items="status_items"
                :rules="selectRule"
                disabled
                dense
                filled
                label="Status"
              ></v-select>
            </v-col>
            <v-col cols="6" md="3">
              <v-select
                v-model="payment_status_item_value"
                :items="payment_status_items"
                :rules="selectRule"
                dense
                filled
                label="Payment Status"
              ></v-select>
            </v-col>
            <v-col cols="6" md="3">
              <v-text-field
                v-model="bilty_charges"
                dense
                filled
                :rules="numberRule"
                label="Bilty Charges"
              ></v-text-field>
            </v-col>
            <v-col cols="6" md="3">
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
        text: "Edit Bilty"
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
        text: "Total weight",
        value: "total_weight",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Labour",
        value: "labour",
        class: "light-blue darken-3 white--text"
      },
      { text: "Rent", value: "rent", class: "light-blue darken-3 white--text" },
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
    status_items: ["registered", "dispatched", "delivered"],
    payment_status_items: ["paid", "unpaid", "monthly"],
    status_item_value: "",
    payment_status_item_value: "unpaid",
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
      return pck_total;
    },
    bilty_total: {
      get() {
        return (
          parseFloat(this.bilty_charges) +
          parseFloat(this.local_charges) +
          parseFloat(this.packages_total)
        );
      },
      set(newName) {
        return newName;
      }
    }
  },
  created() {
    this.getStoredBilty(this.$route.params.id);
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
    getStoredBilty(id) {
      let bilty = this.$store.getters.getEditBiltyById(id);
      if (bilty) {
        this.bilty_no = bilty.bilty_no;
        this.lc_bl_no = bilty.lc_bl_no;
        this.from_city_value = bilty.from;
        this.to_city_value = bilty.to;
        this.sender = bilty.sender;
        this.receiver = bilty.receiver;
        this.receiver_address = bilty.receiver_address;
        this.status_item_value = bilty.status;
        this.payment_status_item_value = bilty.payment_status;
        this.bilty_charges = bilty.bilty_charges;
        this.local_charges = bilty.local_charges;
        this.bilty_total = bilty.bilty_total;
        this.package_total = bilty.package_total;
        this.description = bilty.description;
        this.date = bilty.date;
        this.manual = bilty.manual == "true";
        this.packages = bilty.packages;
        this.customer = bilty.customer ? bilty.customer.id : "";
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
    submit_bilty() {
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
          status_item_value: that.status_item_value,
          payment_status_item_value: that.payment_status_item_value,
          packages_total: that.packages_total,
          bilty_total: that.bilty_total,
          customer_id: -1,
          packages: that.packages
        };
        if (this.customer) {
          bilty.customer_id = this.customer;
        }
        this.$http({
          url: `bilty/${this.$route.params.id}`,
          data: bilty,
          method: "PUT"
        }).then(
          res => {
            this.isUpdating = false;
            this.snackbar = true;
            this.text = "Successfully updated bilty";
            this.$store.dispatch("destroyBilty");
            let user = this.$auth.user();
            if (user.role == 2) {
              this.$router.push({ path: `/admin/bilty/manage` });
            } else {
              this.$router.push({ path: `/operator/bilty/manage` });
            }
          },
          err => {
            this.isUpdating = false;
            this.snackbar = true;
            this.text =
              "Error adding bilty, Failed with Error: " +
              err.response.statusText;
          }
        );
      } else {
        this.isUpdating = false;
        this.snackbar = true;
        this.text = "Validation Failed";
      }
    }
  }
};
</script>