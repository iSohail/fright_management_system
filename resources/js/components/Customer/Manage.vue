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
              @keyup.native.enter="searchCustomer"
              v-model="search"
              append-icon="mdi-magnify"
              append-outer-icon="mdi-lock-reset"
              clear-icon="mdi-close-circle"
              clearable
              @click:append="searchCustomer"
              @click:append-outer="defaultCustomer"
              @click:clear="defaultCustomer"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>

          <v-data-table
            :headers="headers"
            :items="customers"
            :loading="loading"
            :options.sync="options"
            :server-items-length="totalCustomers"
            :footer-props="{
              itemsPerPageOptions: [5, 10, 15],
            }"
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
                          <v-text-field v-model="editedItem.cellNo" label="Cell No"></v-text-field>
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
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="numberRule"
                            v-model="editedItem.income_tax"
                            label="Income Tax"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            :rules="numberRule"
                            v-model="editedItem.sales_tax"
                            label="Sales Tax"
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
                  <v-subheader>Customer Details</v-subheader>
                </v-row>
                <v-row>
                  <v-col cols="12" sm="4">
                    <v-text-field
                      label="Per Kg"
                      placeholder="Per Kg"
                      v-model="item.perKg"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-text-field
                      label="Per Cbm"
                      placeholder="Per Cbm"
                      v-model="item.perCbm"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-text-field
                      label="Per Package"
                      placeholder="Per Package"
                      v-model="item.perPackage"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      label="Income Tax"
                      placeholder="Income Tax"
                      v-model="item.income_tax"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      label="Sales Tax"
                      placeholder="Sales Tax"
                      v-model="item.sales_tax"
                      readonly
                      outlined
                      dense
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-divider></v-divider>
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
      totalCustomers: 0,
      options: {},
      loading: false,
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
          text: "Company",
          value: "company",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Action",
          value: "action",
          sortable: false,
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
        email: "",
        income_tax: "",
        sales_tax: ""
      },
      defaultItem: {
        id: "",
        company: "",
        name: "",
        cellNo: "",
        perKg: "",
        perCbm: "",
        perPackage: "",
        email: "",
        income_tax: "",
        sales_tax: ""
      },
      nameRule: [
        v => !!v || "Field is required",
        v => (v && v.length <= 50) || "Field must be less than 50 characters",
        v =>
          /(?=.*[A-Z])/.test(v) ||
          /(?=.*[a-z])/.test(v) ||
          "Only characters allowed"
      ],
      numberRule: [
        v => {
          if (v >= 0 && v <= 9999999) return true;
          return "Only numbers allowed";
        }
      ],
      selectRule: [v => !!v || "Field is required"]
    };
  },
  watch: {
    // watch for any changes in table options and trigger function
    options: {
      handler() {
        this.getCustomersServerSide().then(data => {
          this.customers = data.items;
          this.totalCustomers = data.total;
        });
      },
      deep: true
    }
  },
  mounted() {
    this.getCustomersServerSide().then(data => {
      this.customers = data.items;
      this.totalCustomers = data.total;
    });
    this.listen();
  },
  methods: {
    // listens for any customers added
    listen() {
      Echo.channel("customers").listen("CustomerAdded", customers => {
        this.getCustomersServerSide().then(data => {
          this.customers = data.items;
          this.totalCustomers = data.total;
        });
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    // clearing up search
    defaultCustomer() {
      this.search = "";
      this.getCustomersServerSide().then(data => {
        this.customers = data.items;
        this.totalCustomers = data.total;
      });
    },
    // if search button is clicked or input given
    searchCustomer() {
      this.getCustomersServerSide().then(data => {
        this.customers = data.items;
        this.totalCustomers = data.total;
      });
    },
    // fetch customers from backend
    getCustomersServerSide() {
      this.loading = true;
      // using async function to wait for the functions to execute and release results
      return new Promise(async (resolve, reject) => {
        const { sortBy, sortDesc, page, itemsPerPage } = this.options;

        var items = [];
        var total = 0;
        var flag = false;

        // if search is given fetch searched item
        // else if check if sortBy and descending is given fetch items orderBy
        // else fetch all customers by Latest
        if (this.search) {
          // searching
          await this.getCustomersFiltered(page, itemsPerPage, this.search).then(
            data => {
              items = data.data;
              total = data.total_items;
              flag = true;
            }
          );
        } else if (sortBy.length === 1 && sortDesc.length === 1) {
          // sorting
          await this.getCustomersSorted(
            page,
            itemsPerPage,
            sortBy[0],
            sortDesc[0]
          ).then(data => {
            items = data.data;
            total = data.total_items;
            flag = true;
          });
        } else {
          // fetch by latest
          await this.getCustomers(page, itemsPerPage).then(data => {
            items = data.data;
            total = data.total_items;
            flag = true;
          });
        }

        // check if functions are executed and release variables
        if (flag == true) {
          this.loading = false;
          resolve({
            items,
            total
          });
        }
      });
    },
    async getCustomers(page, items_per_page) {
      let customers = {};
      await this.$http({
        url: `customer/paginate?page=${page}&per_page=${items_per_page}`,
        method: "GET"
      }).then(
        res => {
          customers.data = this.addCustomerData(res.data.data);
          customers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching customers, please refresh";
        }
      );
      return customers;
    },
    async getCustomersFiltered(page, items_per_page, search) {
      let customers = {};
      await this.$http({
        url: `customer/search?page=${page}&per_page=${items_per_page}&query=${search}`,
        method: "GET"
      }).then(
        res => {
          customers.data = this.addCustomerData(res.data.data);
          customers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Can not find data";
        }
      );
      return customers;
    },
    async getCustomersSorted(page, items_per_page, sortBy, sortDesc) {
      let customers = {};
      await this.$http({
        url: `customer/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
        method: "GET"
      }).then(
        res => {
          customers.data = this.addCustomerData(res.data.data);
          customers.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching customers, please refresh";
        }
      );
      return customers;
    },
    // utility function to make array of objects
    addCustomerData(data) {
      let customers_arr = [];
      for (let customer of data) {
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
          income_tax: customer.attributes.income_tax,
          sales_tax: customer.attributes.sales_tax,
          bilties: []
        };
        for (let customer of customer.relationships.bilties.data) {
          this.getBilty(customer.id).then(res => {
            customers_data.bilties.push(res);
          });
        }
        customers_arr.push(customers_data);
      }
      return customers_arr;
    },
    // fetch customers bilty data
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
    // update customers data
    updateCustomer(editedItem, id) {
      this.$http({
        url: `customer/${id}`,
        data: editedItem,
        method: "PUT"
      }).then(
        res => {
          this.getCustomersServerSide().then(data => {
            this.customers = data.items;
            this.totalCustomers = data.total;
          });
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