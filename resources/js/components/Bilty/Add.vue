<template>
  <v-form v-model="valid" ref="bilty_form">
    <v-container class="px-8 py-2">
      <v-row>
        <v-col cols="12" md="3">
          <v-text-field
            :disabled="!manual"
            :rules="numberRule"
            v-model="bilty_no"
            label="Builty Number"
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

        <v-col cols="12" md="7">
          <v-autocomplete
            v-model="customer"
            :disabled="isUpdating"
            :items="customers"
            chips
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
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-text-field v-model="lc_bl_no" :rules="numberRule" label="L/C - B/L No"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="bilty_charges" :rules="numberRule" label="Bilty Charges"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field v-model="local_charges" :rules="numberRule" label="Local Charges"></v-text-field>
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
          <v-text-field v-model="sender" :rules="nameRule" label="Sender Name"></v-text-field>
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field v-model="receiver" :rules="nameRule" label="Reciever Name"></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="12">
          <v-textarea
            label="Reciever Address"
            rows="2"
            row-height="20"
            v-model="receiver_address"
            class="ma-1"
            :rules="descriptionRule"
          ></v-textarea>
        </v-col>
      </v-row>

      <v-row>
        <v-col class="d-flex" cols="6" md="4">
          <v-select
            v-model="status_item_value"
            :items="status_items"
            :rules="selectRule"
            label="Status"
          ></v-select>
        </v-col>
        <v-col cols="6" md="4">
          <v-select
            v-model="payment_status_item_value"
            :items="payment_status_items"
            :rules="selectRule"
            label="Payment Status"
          ></v-select>
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
                <v-card-title>
                  <span class="headline" color="blue">{{ formTitle }}</span>
                </v-card-title>

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
                        <v-col cols="12" sm="8" md="6">
                          <v-text-field
                            v-model="editedItem.quantity"
                            :rules="numberRule"
                            label="Quantity"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="8" md="6">
                          <v-select
                            v-model="editedItem.unit"
                            :items="unit_items"
                            :rules="selectRule"
                            label="Unit"
                          ></v-select>
                          <!-- <v-text-field v-model="editedItem.unit" :rules="numberRule" label="Unit"></v-text-field> -->
                        </v-col>
                        <v-col cols="12" sm="8" md="4">
                          <v-text-field
                            v-model="editedItem.total_weight"
                            :rules="numberRule"
                            label="Total Weight"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="8" md="4">
                          <v-text-field v-model="editedItem.rent" :rules="numberRule" label="Rent"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="8" md="4">
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
      <v-spacer></v-spacer>
      <v-btn class="my-4 float-right" @click="submit_bilty">Submit</v-btn>
    </v-container>
  </v-form>
</template>


<script>
export default {
  data: () => ({
    from_city_items: ["Karachi", "Lahore", "Sheikhupura", "Islamabad"],
    to_city_items: ["Lahore", "Sheikhupura", "Karachi", "Islamabad"],
    from_city_value: "Karachi",
    to_city_value: "Lahore",
    manual: false,
    bilty_no: "",
    lc_bl_no: "",
    bilty_charges: "",
    local_charges: "",
    sender: "",
    receiver: "",
    receiver_address: "",
    customer: [],
    isUpdating: false,
    name: "",
    customers: [],
    dialog: false,
    headers: [
      { text: "Description", align: "left", value: "description" },
      { text: "Unit", value: "unit" },
      { text: "Quantity", value: "quantity" },
      { text: "Total weight", value: "total_weight" },
      { text: "Labour", value: "labour" },
      { text: "Rent", value: "rent" },
      { text: "Action", value: "action", sortable: false }
    ],
    packages: [],
    editedIndex: -1,
    editedItem: {
      description: "",
      quantity: "",
      unit: "",
      total_weight: "",
      rent: "",
      labour: ""
    },
    defaultItem: {
      description: "",
      quantity: "",
      unit: "",
      total_weight: "",
      rent: "",
      labour: ""
    },
    status_items: ["Registered", "Dispatched", "Delivered"],
    payment_status_items: ["Paid", "Unpaid", "Monthly"],
    status_item_value: "",
    payment_status_item_value: "",
    unit_items: ["kg", "cbm", "pck"],
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
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
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
      if (val) {
        setTimeout(() => (this.isUpdating = false), 3000);
      }
    }
  },

  methods: {
    getLastBiltyNo() {
      this.$http({
        url: `bilties/last`,
        method: "GET"
      }).then(
        res => {
          this.bilty_no = parseInt(res.data);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
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
        () => {
          console.log("error occured");
          // this.has_error = true
        }
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
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
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
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
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
      if (this.$refs.bilty_form.validate()) {
        let that = this;
        let bilty = {
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
          customer_id: that.customer,
          packages: that.packages
        };
        console.log(bilty);
        this.$http({
          url: `bilty/create`,
          data: bilty,
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