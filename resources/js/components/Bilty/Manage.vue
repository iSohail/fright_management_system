<template>
  <v-container>
    <v-card>
      <v-card-title>
        Manage Bilty
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
        :single-expand="singleExpand"
        :expanded.sync="expanded"
      >
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
                  label="From"
                  placeholder="From"
                  v-model="item.from"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field label="To" placeholder="To" v-model="item.to" readonly outlined dense></v-text-field>
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
  </v-container>
</template>


<script>
export default {
  data() {
    return {
      search: "",
      expanded: [],
      singleExpand: false,
      headers_packages: [
        { text: "Description", align: "left", value: "description" },
        { text: "Unit", value: "unit" },
        { text: "Quantity", value: "quantity" },
        { text: "Total weight", value: "total_weight" },
        { text: "Labour", value: "labour" },
        { text: "Rent", value: "rent" }
      ],
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
        { text: "Status", value: "status" },
        { text: "Payment", value: "payment_status" },
        { text: "Action", value: "action", sortable: false },
        { text: "", value: "data-table-expand" }
      ],
      bilties: [],
      packages: []
    };
  },
  created() {
    this.getBilties();
  },
  methods: {
    getBilties() {
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
              from: bilty.attributes.from,
              to: bilty.attributes.to,
              sender: bilty.attributes.sender,
              receiver: bilty.attributes.receiver,
              receiver_address: bilty.attributes.receiver_address,
              status: bilty.attributes.status,
              payment_status: bilty.attributes.payment_status,
              bilty_charges: bilty.attributes.bilty_charges,
              local_charges: bilty.attributes.local_charges,
              packages: [],
              package_total: 0,
              total_amount: 0
            };
            bilty_data.total_amount =
              parseFloat(bilty_data.bilty_charges) +
              parseFloat(bilty_data.local_charges);
            this.getCustomer(bilty.relationships.customer.data.id).then(res => {
              bilty_data.customer = res;
            });
            for (let pck of bilty.relationships.packages.data) {
              console.log(pck.id);
              this.getPackage(pck.id).then(res => {
                bilty_data.package_total +=
                  parseFloat(res.labour) + parseFloat(res.rent);
                bilty_data.total_amount +=
                  parseFloat(res.labour) + parseFloat(res.rent);
                bilty_data.packages.push(res);
              });
            }
            bilties.push(bilty_data);
          }
          this.bilties = bilties;
          // console.log(bilties);
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
            description: res.data.attributes.description,
            unit: res.data.attributes.unit,
            quantity: res.data.attributes.quantity,
            total_weight: res.data.attributes.total_weight,
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
    }
  }
};
</script>