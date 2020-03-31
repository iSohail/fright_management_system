<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>
    <v-card style="height: 100%; min-height: 100vh" dark flat tile>
      <v-card-title class="px-8 pt-8 headline">MANAGE CUSTOMER</v-card-title>
      <v-card-text>
        <v-card class="mx-4 my-2 mb-4">
          <v-card-title>
            Customers
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
            :headers="headers"
            :items="customers"
            :search="search"
            show-expand
            item-key="id"
            :single-expand="singleExpand"
            :expanded.sync="expanded"
          >
            <template v-slot:top>
              <v-dialog v-model="dialog" max-width="800px">
                <v-card>
                  <v-card-title>
                    <span class="headline">Edit Item</span>
                  </v-card-title>

                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field :rules="nameRule" v-model="editedItem.name" label="Name"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="selectRule"
                            v-model="editedItem.cellNo"
                            label="Cell No"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="selectRule"
                            v-model="editedItem.company"
                            label="Company"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="numberRule"
                            v-model="editedItem.perKg"
                            label="Per Kg"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="numberRule"
                            v-model="editedItem.perCbm"
                            label="Per Cbm"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="numberRule"
                            v-model="editedItem.perPackage"
                            label="Per Package"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn class="primary mr-2" small @click="editItem(item)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
            </template>
            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length" class="black">
                <v-row>
                  <v-subheader>Bilty Details</v-subheader>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-data-table
                      :headers="headers_bilties"
                      :items="item.bilties"
                      :items-per-page="5"
                    ></v-data-table>
                  </v-col>
                </v-row>
              </td>
            </template>
          </v-data-table>
        </v-card>
        <v-snackbar v-model="snackbar">
          {{ text }}
          <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
        </v-snackbar>
      </v-card-text>
    </v-card>
  </div>
</template>



<script>
export default {
  data() {
    return {
      bread_crumb_items: [
        {
          text: "Customer"
        },
        {
          text: "Manage Customer"
        }
      ],
      snackbar: false,
      text: "",
      search: "",
      expanded: [],
      singleExpand: false,
      dialog: false,
      headers_bilties: [
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
          text: "Payment",
          value: "payment_status",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Status",
          value: "status",
          class: "light-blue darken-3 white--text"
        }
      ],
      headers: [
        {
          text: "Customer No",
          align: "left",
          value: "no",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Name",
          value: "name",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Email",
          value: "email",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Cell No",
          value: "cellNo",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Per Kg",
          value: "perKg",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Per Cbm",
          value: "perCbm",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Per Package",
          value: "perPackage",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Action",
          value: "action",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "",
          value: "data-table-expand",
          class: "light-blue darken-3 white--text"
        }
      ],
      customers: [],
      editedIndex: -1,
      editedItem: {
        id: "",
        company: "",
        name: "",
        cellNo: "",
        perKg: "",
        perCbm: "",
        perPackage: "",
        email: ""
      },
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
    };
  },
  mounted() {
    this.getCustomers();
    this.listen();
  },
  methods: {
    listen() {
      Echo.channel("customers").listen("CustomerAdded", customers => {
        let customers_arr = [];

        for (let customer of customers.customers) {
          let customers_data = {
            id: customer.id,
            no: customer.attributes.customer_no,
            name: customer.attributes.name,
            cellNo: customer.attributes.cell_no,
            email: customer.attributes.email,
            company: customer.attributes.company,
            perKg: customer.attributes.per_kg_rate,
            perCbm: customer.attributes.per_cbm_rate,
            perPackage: customer.attributes.per_pck_rate,
            bilties: []
          };
          for (let customer of customer.relationships.bilties.data) {
            this.getBilty(customer.id).then(res => {
              customers_data.bilties.push(res);
            });
          }
          customers_arr.push(customers_data);
        }
        this.customers = customers_arr;
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    async getBilty(id) {
      let bilty = {};
      await this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          bilty = {
            no: res.data.attributes.bilty_no,
            lc_bl_no: res.data.attributes.lg_bl_no,
            manual: res.data.attributes.manual,
            sender: res.data.attributes.sender,
            receiver: res.data.attributes.receiver,
            payment_status: res.data.attributes.payment_status,
            status: res.data.attributes.status
          };
        },
        () => {}
      );
      return bilty;
    },
    getCustomers() {
      this.$http({
        url: `customer`,
        method: "GET"
      }).then(
        res => {
          let customers_arr = [];

          for (let customer of res.data) {
            let customers_data = {
              id: customer.id,
              no: customer.attributes.customer_no,
              name: customer.attributes.name,
              email: customer.attributes.email,
              company: customer.attributes.company,
              cellNo: customer.attributes.cell_no,
              perKg: customer.attributes.per_kg_rate,
              perCbm: customer.attributes.per_cbm_rate,
              perPackage: customer.attributes.per_pck_rate,
              bilties: []
            };
            for (let customer of customer.relationships.bilties.data) {
              this.getBilty(customer.id).then(res => {
                customers_data.bilties.push(res);
              });
            }
            customers_arr.push(customers_data);
          }
          this.customers = customers_arr;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching customers, please refresh";
        }
      );
    },
    updateCustomer(editedItem, id) {
      this.$http({
        url: `customer/${id}`,
        data: editedItem,
        method: "PUT"
      }).then(
        res => {
          this.getCustomers();
          this.snackbar = true;
          this.text = "Success updating customer";
        },
        () => {
          this.snackbar = true;
          this.text = "Error updating customer";
        }
      );
    },

    editItem(item) {
      this.editedIndex = this.customers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        this.updateCustomer(this.editedItem, this.editedItem.id);
      } else {
        this.customers.push(this.editedItem);
      }
      this.close();
    }
  }
};
</script>