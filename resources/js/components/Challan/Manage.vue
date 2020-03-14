<template>
  <v-container>
    <v-card>
      <v-card-title>
        Manage Challan
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
        :items="challans"
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
              <v-subheader>Challan Details</v-subheader>
            </v-row>
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Driver Name"
                  placeholder="Driver Name"
                  v-model="item.driver_name"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Agent Name"
                  placeholder="Agent Name"
                  v-model="item.agent_name"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Transport"
                  placeholder="Transport"
                  v-model="item.transport"
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
                <v-text-field label="To" placeholder="To" v-model="item.to" readonly outlined dense></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Total Amount"
                  placeholder="Total Amount"
                  v-model="item.total_amount"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Expenses"
                  placeholder="Expenses"
                  v-model="item.expenses"
                  readonly
                  outlined
                  dense
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  label="Grand Total"
                  placeholder="Grand Total"
                  v-model="item.grand_total"
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
                <v-data-table :headers="headers_bilties" :items="item.bilties" :items-per-page="5"></v-data-table>
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
      headers_bilties: [
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
        { text: "Payment", value: "payment_status" }
      ],
      headers: [
        {
          text: "Challan No",
          align: "left",
          sortable: false,
          value: "no"
        },
        { text: "Manual", value: "manual" },
        { text: "Truck No", value: "truck_no" },
        { text: "Permit No", value: "permit_no" },
        { text: "Grand Total", value: "grand_total" },
        { text: "Action", value: "action", sortable: false },
        { text: "", value: "data-table-expand" }
      ],
      challans: [],
      packages: []
    };
  },
  created() {
    this.getChallans();
  },
  methods: {
    getChallans() {
      this.$http({
        url: `challan`,
        method: "GET"
      }).then(
        res => {
          console.log(res.data);
          let challans = [];
          for (let challan of res.data) {
            let challan_data = {
              id: challan.id,
              no: challan.attributes.challan_no,
              truck_no: challan.attributes.truck_no,
              from: challan.attributes.from,
              permit_no: challan.attributes.permit_no,
              to: challan.attributes.to,
              transport: challan.attributes.transport,
              driver_name: challan.attributes.driver_name,
              agent_name: challan.attributes.agent_name,
              cnic: challan.attributes.cnic,
              total_amount: challan.attributes.total_amount,
              expenses: challan.attributes.expenses,
              grand_total: challan.attributes.grand_total,
              bilties: []
            };
            for (let bilty of challan.relationships.bilties.data) {
              console.log(bilty.id);
              this.getBilty(bilty.id).then(res => {
                challan_data.bilties.push(res);
              });
            }
            challans.push(challan_data);
          }
          this.challans = challans;
          // console.log(bilties);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
    },
    async getBilty(id) {
      let bilty = {};
      await this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          console.log(res);
          bilty = {
            no: res.data.attributes.bilty_no,
            lc_bl_no: res.data.attributes.lg_bl_no,
            manual: res.data.attributes.manual,
            sender: res.data.attributes.sender,
            receiver: res.data.attributes.receiver,
            payment_status: res.data.attributes.payment_status
          };
          console.log(bilty);
        },
        () => {
          console.log("error occured");
          // this.has_error = true
        }
      );
      // console.log(customer);
      return bilty;
    },
    editItem(item) {
      let user = this.$auth.user();
      if (user.role == 2) {
        this.$router.push({ path: `/admin/challan/edit/${item.id}` });
      } else {
        this.$router.push({ path: `/operator/challan/edit/${item.id}` });
      }
    }
  }
};
</script>