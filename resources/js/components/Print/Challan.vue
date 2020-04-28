<template>
  <div id="app">
    <v-app id="inspire">
      <v-container class="padding-on-print" style="min-width: 1000px !important">
        <v-toolbar color="white" dense flat tile>
          <v-toolbar-items>
            <v-btn light text @click="print">
              <v-icon>mdi-printer</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-row class="px-4">
          <v-col cols="4">
            <v-simple-table>
              <template v-slot:default>
                <tbody class="subtitle-2">
                  <tr>
                    <td>Date</td>
                    <td class="font-weight-regular black--text">{{date}}</td>
                  </tr>
                  <tr>
                    <td>Challan No</td>
                    <td class="font-weight-regular black--text">{{challan_no}}</td>
                  </tr>
                  <tr>
                    <td>Truck No</td>
                    <td class="font-weight-regular black--text">{{truck_no}}</td>
                  </tr>
                  <tr>
                    <td>Permit No</td>
                    <td class="font-weight-regular black--text">{{permit_no}}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
          <v-col cols="4">
            <v-simple-table>
              <template v-slot:default>
                <tbody class="subtitle-2">
                  <tr>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>From</td>
                    <td class="font-weight-regular black--text">{{from}}</td>
                  </tr>
                  <tr>
                    <td>To</td>
                    <td class="font-weight-regular black--text">{{to}}</td>
                  </tr>
                  <tr>
                    <td>Transport</td>
                    <td class="font-weight-regular black--text">{{transport}}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
          <v-col cols="4">
            <v-simple-table>
              <template v-slot:default>
                <tbody class="subtitle-2">
                  <tr>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>Driver</td>
                    <td class="font-weight-regular black--text">{{driver_name}}</td>
                  </tr>
                  <tr>
                    <td>Agent</td>
                    <td class="font-weight-regular black--text">{{agent_name}}</td>
                  </tr>
                  <tr>
                    <td>CNIC</td>
                    <td class="font-weight-regular black--text">{{cnic}}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-data-table
              light
              :headers="headers_selected_item"
              :items="selected_items"
              mobile-breakpoint="NaN"
              hide-default-footer
              disable-pagination
              disable-sort
              disable-filtering
              flat
              class="table_data_style"
            ></v-data-table>
          </v-col>
        </v-row>
        <v-row class="mx-4" justify="end">
          <v-simple-table>
            <template v-slot:default>
              <tbody>
                <tr class="blue lighten-5">
                  <td class="title black--text font-weight-medium subtitle-1">Gross Total Rs.</td>
                  <td
                    class="title black--text font-weight-medium subtitle-1"
                  >{{selected_gross_amount}}</td>
                </tr>
                <tr>
                  <td class="title mb-2 pr-4 subtitle-2">- Expenses: Rs.</td>
                  <td class="title mb-2 pr-4 subtitle-2">{{selected_expenses}}</td>
                </tr>
                <tr class="blue lighten-5">
                  <td class="title black--text font-weight-medium subtitle-1">Net Total: Rs.</td>
                  <td class="title black--text font-weight-medium subtitle-1">{{selected_net_total}}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-row>
      </v-container>
    </v-app>
  </div>
</template>


<script>
export default {
  data() {
    return {
      challan_no: "",
      truck_no: "",
      permit_no: "",
      date: "",
      from: "",
      to: "",
      transport: "",
      driver_name: [],
      agent_name: "",
      cnic: "",
      selected_gross_amount: "",
      selected_expenses: "",
      selected_net_total: "",
      selected_items: [],
      // count: 0,
      headers_selected_item: [
        {
          text: "#",
          align: "left",
          value: "count",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Sender",
          value: "sender",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Receiver",
          value: "receiver",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Qt",
          value: "quantity",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Description",
          value: "description",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Weight",
          value: "weight",
          class: "blue lighten-4 black--text subtitle-2"
        },
        {
          text: "Fright",
          value: "rent",
          class: "blue lighten-4 black--text subtitle-2"
        }
      ]
    };
  },
  created() {
    let challan = this.$store.getters.getChallanById(this.$route.query.id);
    if (challan) {
      this.challan_no = challan.challan_no;
      this.from = challan.from;
      this.to = challan.to;
      this.date = challan.date;
      this.truck_no = challan.truck_no;
      this.permit_no = challan.permit_no;
      this.driver_name = challan.driver_name;
      this.agent_name = challan.agent_name;
      this.cnic = challan.cnic;
      this.transport = challan.transport;
      this.selected_gross_amount = challan.total_amount;
      this.selected_expenses = challan.expenses;
      this.selected_net_total = challan.grand_total;
      challan.bilties.forEach((element, index) => {
        this.getBilty(element, index + 1);
      });
    }
  },
  methods: {
    getBilty(id, count) {
      this.$http({
        url: `bilty/${id}`,
        method: "GET"
      }).then(
        res => {
          let item_data = [];
          let item = {
            count: count,
            bilty_no: res.data.attributes.bilty_no,
            sender: res.data.attributes.sender,
            receiver: res.data.attributes.receiver,
            description: res.data.attributes.description,
            rent: res.data.attributes.bilty_total,
            weight: 0,
            quantity: 0
          };

          for (let pck of res.data.relationships.packages.data) {
            this.getPackage(pck.id).then(res => {
              item.weight += parseFloat(res.total_weight);
              item.quantity += parseFloat(res.quantity);
            });
          }
          this.selected_items.push(item);
        },
        () => {}
      );
    },
    async getPackage(id) {
      let pck = {};
      await this.$http({
        url: `package/${id}`,
        method: "GET"
      }).then(
        res => {
          pck = {
            id: res.data.id,
            quantity: res.data.attributes.quantity,
            total_weight: res.data.attributes.total_weight
          };
        },
        () => {}
      );
      return pck;
    },
    print() {
      window.print();
    }
  }
};
</script>

<style scoped>
@page {
  size: auto; /* auto is the initial value */

  /* this affects the margin in the printer settings */
  margin: 5mm 5mm 5mm 5mm;
}
</style>