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
      <v-card-title class="px-8 pt-8 headline">MANAGE BILTY</v-card-title>

      <v-card class="mx-8 my-2 mb-4">
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
          :headers="headers"
          :items="bilties"
          :search="search"
          show-expand
          item-key="id"
          :loading="loading"
          loading-text="Loading... Please wait"
          :single-expand="singleExpand"
          :expanded.sync="expanded"
        >
          <template v-slot:item.action="{ item }">
            <v-row>
              <v-col cols="6" class="px-0">
                <v-btn class="primary" small @click="editItem(item)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="6" class="px-0">
                <v-btn class="red mx-sm-3" small @click="printItem(item)">
                  <v-icon>mdi-printer</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length" class="black">
              <v-row>
                <v-subheader>Customer Details</v-subheader>
              </v-row>
              <v-row v-if="item.customer">
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Number"
                    placeholder="Number"
                    v-model="item.customer.customer_no"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Name"
                    placeholder="Name"
                    v-model="item.customer.name"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="4">
                  <v-text-field
                    label="Company"
                    placeholder="Company"
                    v-model="item.customer.company"
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
                <v-col cols="12" md="6">
                  <v-text-field
                    label="Receiver"
                    placeholder="Receiver"
                    v-model="item.receiver"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    label="From"
                    placeholder="From"
                    v-model="item.from"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    label="To"
                    placeholder="To"
                    v-model="item.to"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row class="mt-0">
                <v-col cols="12" class="pt-sm-0">
                  <v-textarea
                    label="Receiver Address"
                    auto-grow
                    outlined
                    readonly
                    v-model="item.receiver_address"
                    rows="1"
                    row-height="10"
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-divider></v-divider>
              <v-row>
                <v-subheader>Package Details</v-subheader>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-data-table
                    :headers="headers_packages"
                    :items="item.packages"
                    :items-per-page="5"
                  ></v-data-table>
                </v-col>
              </v-row>
              <v-divider></v-divider>
              <v-row>
                <v-subheader>Account Receivable</v-subheader>
              </v-row>
              <v-row>
                <v-col cols="12" md="3">
                  <v-text-field
                    label="Bilty Charges"
                    placeholder="Bilty Charges"
                    v-model="item.bilty_charges"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    label="Local Charges"
                    placeholder="Local Charges"
                    v-model="item.local_charges"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    label="Packages Total"
                    placeholder="Packages Total"
                    v-model="item.package_total"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <v-text-field
                    label="Total Amount"
                    placeholder="Total Amount"
                    v-model="item.total_amount"
                    readonly
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
            </td>
          </template>
        </v-data-table>
      </v-card>
    </v-card>
    <v-snackbar v-model="snackbar">
      {{ text }}
      <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>


<script>
export default {
  data() {
    return {
      bread_crumb_items: [
        {
          text: "Bilty"
        },
        {
          text: "Manage Bilty"
        }
      ],
      snackbar: false,
      text: "",
      search: "",
      expanded: [],
      singleExpand: false,
      loading: false,
      headers_packages: [
        {
          text: "Description",
          align: "left",
          value: "description",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Unit",
          value: "unit",
          class: "light-blue darken-3 white--text"
        },
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
          text: "Volume",
          value: "total_volume",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Labour",
          value: "labour",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Rate",
          value: "rate",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Rent",
          value: "rent",
          class: "light-blue darken-3 white--text"
        }
      ],
      headers: [
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
        // {
        //   text: "Receiver",
        //   value: "receiver",
        //   class: "light-blue darken-3 white--text"
        // },
        {
          text: "Status",
          value: "status",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Payment",
          value: "payment_status",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "Created",
          value: "created_at",
          class: "light-blue darken-3 white--text"
        },
        {
          text: "User",
          value: "user_name",
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
      bilties: [],
      packages: []
    };
  },
  created() {
    this.getBilties();
    this.listen();
  },
  methods: {
    listen() {
      Echo.channel("bilties").listen("BiltyAdded", bilties => {
        console.log(bilties);
        let bilties_arr = [];

        for (let bilty of bilties.bilties) {
          let bilty_data = {
            id: bilty.id,
            no: bilty.attributes.bilty_no,
            lc_bl_no: bilty.attributes.lg_bl_no,
            date: bilty.attributes.created_at.slice(0, 10),
            manual: bilty.attributes.manual,
            from: bilty.attributes.from,
            to: bilty.attributes.to,
            sender: bilty.attributes.sender,
            receiver: bilty.attributes.receiver,
            receiver_address: bilty.attributes.receiver_address,
            status: bilty.attributes.status,
            payment_status: bilty.attributes.payment_status,
            created_at: bilty.attributes.created_at.slice(0, 10),
            bilty_charges: bilty.attributes.bilty_charges,
            local_charges: bilty.attributes.local_charges,
            user_name: bilty.relationships.user.data.user_name,
            packages: [],
            package_total: bilty.attributes.packages_total,
            total_amount: bilty.attributes.bilty_total
          };
          if (bilty.relationships.customer.data) {
            this.getCustomer(bilty.relationships.customer.data.id).then(res => {
              bilty_data.customer = res;
            });
          }
          for (let pck of bilty.relationships.packages.data) {
            console.log(pck.id);
            this.getPackage(pck.id).then(res => {
              bilty_data.packages.push(res);
            });
          }
          bilties_arr.push(bilty_data);
        }
        this.bilties = bilties_arr;
        this.snackbar = true;
        this.text = "New data added";
      });
    },
    getBilties() {
      this.loading = true;
      this.$http({
        url: `bilty`,
        method: "GET"
      }).then(
        res => {
          console.log(res.data);
          let bilties = [];
          for (let bilty of res.data) {
            let bilty_data = {
              id: bilty.id,
              no: bilty.attributes.bilty_no,
              lc_bl_no: bilty.attributes.lg_bl_no,
              manual: bilty.attributes.manual,
              date: bilty.attributes.created_at.slice(0, 10),
              description: bilty.attributes.description,
              from: bilty.attributes.from,
              to: bilty.attributes.to,
              sender: bilty.attributes.sender,
              receiver: bilty.attributes.receiver,
              receiver_address: bilty.attributes.receiver_address,
              status: bilty.attributes.status,
              lock: bilty.attributes.lock,
              payment_status: bilty.attributes.payment_status,
              created_at: bilty.attributes.created_at.slice(0, 10),
              bilty_charges: bilty.attributes.bilty_charges,
              local_charges: bilty.attributes.local_charges,
              user_name: bilty.relationships.user.data.user_name,
              packages: [],
              package_total: bilty.attributes.packages_total,
              total_amount: bilty.attributes.bilty_total
            };
            if (bilty.relationships.customer.data) {
              this.getCustomer(bilty.relationships.customer.data.id).then(
                res => {
                  bilty_data.customer = res;
                }
              );
            }
            for (let pck of bilty.relationships.packages.data) {
              console.log(pck.id);
              this.getPackage(pck.id).then(res => {
                bilty_data.packages.push(res);
              });
            }
            bilties.push(bilty_data);
          }
          this.bilties = bilties;
          this.loading = false;
          // console.log(bilties);
        },
        () => {
          this.loading = false;
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
            id: id,
            customer_no: res.data.attributes.customer_no,
            name: res.data.attributes.name,
            company: res.data.attributes.company
          };
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return customer;
    },
    async getPackage(id) {
      let pck = {};
      await this.$http({
        url: `package/${id}`,
        method: "GET"
      }).then(
        res => {
          pck = {
            id: id,
            description: res.data.attributes.description,
            unit: res.data.attributes.unit,
            quantity: res.data.attributes.quantity,
            total_weight: res.data.attributes.total_weight,
            total_volume: res.data.attributes.total_volume,
            labour: res.data.attributes.labour,
            rate: res.data.attributes.rate,
            labour: res.data.attributes.labour,
            rent: res.data.attributes.rent
          };
          console.log(pck);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return pck;
    },
    editItem(item) {
      if (item.lock == "false") {
        item.bilty_no = item.no;
        this.$store.dispatch("destroyEditBilty");
        this.$store.dispatch("createEditBilty", item);
        let user = this.$auth.user();
        if (user.role == 2) {
          this.$router.push({ path: `/admin/bilty/edit/${item.id}` });
        } else {
          this.$router.push({ path: `/operator/bilty/edit/${item.id}` });
        }
      } else {
        this.snackbar = true;
        this.text = "Bilty is locked";
      }
    },
    printItem(item) {
      console.log(item);
      item.bilty_no = item.no;
      this.$store.dispatch("destroyBilty");
      this.$store.dispatch("createBilty", item);
      console.log(item.no);

      let routeData = this.$router.resolve({
        name: "invoice.bilty",
        query: { id: item.bilty_no, manual: item.manual }
      });
      window.open(routeData.href, "_blank");
    }
  }
};
</script>