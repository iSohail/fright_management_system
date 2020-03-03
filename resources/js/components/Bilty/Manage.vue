<template>
  <v-container>
    <v-card>
      <v-card-title>
        Receiver Details
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table :headers="headers" :items="desserts" :search="search">
        <template v-slot:item.calories="{ item }">
          <v-chip :color="getColor(item.calories)" dark>{{ item.calories }}</v-chip>
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
      headers: [
        {
          text: "Builty No",
          align: "left",
          sortable: false,
          value: "name"
        },
        { text: "Sender", value: "Sender" },
        { text: "Quantity", value: "Quantity" },
        { text: "Description", value: "Description" },
        { text: "Weight", value: "Weight" },
        { text: "Amount", value: "Amount" },
        { text: "Status", value: "Status" }
      ],
      desserts: [
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: true
        },
        {
          name: "hammad",
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        },
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        },
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        },
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        },
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        },
        {
          name: 123,
          Sender: 159,
          Quantity: 6.0,
          Description: 24,
          Weight: 4.0,
          Amount: "1%",
          Status: false
        }
      ]
    };
  }
};
</script>