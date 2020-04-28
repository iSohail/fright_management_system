<template>
  <div>
    <v-alert color="light-blue darken-3 mb-0" dark dense tile flat>
      <v-breadcrumbs class="py-3" dark :items="bread_crumb_items">
        <template v-slot:item="{ item }">
          <v-breadcrumbs-item>{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
        </template>
      </v-breadcrumbs>
    </v-alert>

    <v-card
      style="height: 100%; min-height: 100vh"
      :loading="isUpdating"
      dark
      flat
      tile
      :disabled="isUpdating"
    >
      <template v-slot:progress>
        <v-progress-linear absolute color="blue lighten-3" height="4" indeterminate></v-progress-linear>
      </template>
      <v-card-title class="px-8 pt-8 headline">USERS</v-card-title>
      <v-card-text>
        <v-form v-model="valid" ref="form">
          <v-container>
            <v-card class="mx-auto pa-8" style="max-width: 600px ;height: 100%;">
              <v-card-title class="mx-auto">Add New User</v-card-title>
              <v-row>
                <v-col cols="6">
                  <v-text-field v-model="name" :rules="nameRule" label="Name." filled dense></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="user_name"
                    :rules="selectRule"
                    label="User Name"
                    filled
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field v-model="email" :rules="selectRule" label="Email" filled dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="password"
                    :rules="selectRule"
                    label="Password"
                    filled
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-select
                    v-model="role"
                    :rules="selectRule"
                    :items="roles"
                    dense
                    filled
                    label="Role"
                  ></v-select>
                </v-col>
              </v-row>

              <v-row justify="end">
                <v-btn class="px-8 mx-4" depressed color="primary" @click="save">Save</v-btn>
              </v-row>
              <v-snackbar v-model="snackbar">
                {{ text }}
                <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
              </v-snackbar>
            </v-card>
          </v-container>
        </v-form>
        <v-card>
          <v-card-title>
            Users
            <v-spacer></v-spacer>
            <v-text-field
              @keyup.native.enter="searchUser"
              v-model="search"
              append-icon="mdi-magnify"
              append-outer-icon="mdi-lock-reset"
              clear-icon="mdi-close-circle"
              clearable
              @click:append="searchUser"
              @click:append-outer="defaultUser"
              @click:clear="defaultUser"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="users"
            :options.sync="options"
            :server-items-length="totalUser"
            :footer-props="{
              itemsPerPageOptions: [5, 10, 15],
            }"
            :loading="loading"
            loading-text="Loading... Please wait"
            item-key="id"
          >
            <template v-slot:item.action="{ item }">
              <v-row>
                <v-col cols="6" class="px-0 text-right">
                  <v-btn
                    v-if="item.role != 'Admin'"
                    class="red mr-sm-3"
                    small
                    @click="deleteItem(item)"
                  >
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </v-col>
                <v-col cols="6" class="px-0 text-left">
                  <v-btn class="primary mx-sm-3" small @click="openDialog(item)">
                    <v-icon>mdi-lock</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
        </v-card>
      </v-card-text>

      <v-snackbar v-model="snackbar">
        {{ text }}
        <v-btn color="pink" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-card>
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-form ref="change_pass_form">
          <v-card>
            <v-card-title>
              <span class="headline">Change password {{changePasswordItem.name}}</span>
            </v-card-title>
            <v-card-text>
              <v-text-field label="password" :rules="selectRule" v-model="password" required></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
              <v-btn color="blue darken-1" text @click="changePassword()">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
export default {
  data: () => ({
    bread_crumb_items: [
      {
        text: "Users"
      }
    ],
    dialog: false,
    name: "",
    user_name: "",
    email: "",
    password: "",
    role: "",
    roles: ["operator", "sender", "receiver"],
    search: "",
    snackbar: false,
    isUpdating: false,
    valid: true,
    text: "",
    users: [],
    password: "",
    changePasswordItem: "",
    loading: false,
    totalUser: 0,
    options: {},
    headers: [
      {
        text: "Name",
        align: "start",
        value: "name",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "User Name",
        value: "user_name",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Email",
        value: "email",
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Role",
        value: "role",
        sortable: false,
        class: "light-blue darken-3 white--text"
      },
      {
        text: "Action",
        value: "action",
        sortable: false,
        class: "light-blue darken-3 white--text"
      }
    ],
    nameRule: [
      v => !!v || "Field is required",
      v => (v && v.length <= 30) || "Field must be less than 30 characters",
      v =>
        /(?=.*[A-Z])/.test(v) ||
        /(?=.*[a-z])/.test(v) ||
        "Only characters allowed"
    ],
    selectRule: [v => !!v || "Field is required"]
  }),
  watch: {
    // watch for any changes in table options and trigger function
    options: {
      handler() {
        this.getUsersServerSide().then(data => {
          this.users = data.items;
          this.totalUser = data.total;
        });
      },
      deep: true
    }
  },
  mounted() {
    this.getUsersServerSide().then(data => {
      this.users = data.items;
      this.totalUser = data.total;
    });
  },
  methods: {
    // clearing up search
    defaultUser() {
      this.search = "";
      this.getUsersServerSide().then(data => {
        this.users = data.items;
        this.totalUser = data.total;
      });
    },
    // if search button is clicked or input given
    searchUser() {
      this.getUsersServerSide().then(data => {
        this.users = data.items;
        this.totalUser = data.total;
      });
    },
    // fetch users from backend
    getUsersServerSide() {
      this.loading = true;
      // using async function to wait for the functions to execute and release results
      return new Promise(async (resolve, reject) => {
        const { sortBy, sortDesc, page, itemsPerPage } = this.options;
        var items = [];
        var total = 0;
        var flag = false;

        // if search is given fetch searched item
        // else if check if sortBy and descending is given fetch items orderBy
        // else fetch all users by Latest
        if (this.search) {
          // searching
          await this.getUsersFiltered(page, itemsPerPage, this.search).then(
            data => {
              items = data.data;
              total = data.total_items;
              flag = true;
            }
          );
        } else if (sortBy.length === 1 && sortDesc.length === 1) {
          // sorting
          await this.getUsersSorted(
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
          await this.getUsers(page, itemsPerPage).then(data => {
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
    async getUsersFiltered(page, items_per_page, search) {
      let users = {};
      await this.$http({
        url: `user/search?page=${page}&per_page=${items_per_page}&query=${search}`,
        method: "GET"
      }).then(
        res => {
          users.data = this.addUserData(res.data.data);
          users.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching users, please refresh";
        }
      );
      return users;
    },
    async getUsersSorted(page, items_per_page, sortBy, sortDesc) {
      let users = {};
      await this.$http({
        url: `user/sort?page=${page}&per_page=${items_per_page}&sort_by=${sortBy}&sort_desc=${sortDesc}`,
        method: "GET"
      }).then(
        res => {
          users.data = this.addUserData(res.data.data);
          users.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching users, please refresh";
        }
      );
      return users;
    },
    async getUsers(page, items_per_page) {
      let users = {};
      await this.$http({
        url: `user/paginate?page=${page}&per_page=${items_per_page}`,
        method: "GET"
      }).then(
        res => {
          users.data = this.addUserData(res.data.data);
          users.total_items = res.data.meta.total;
        },
        () => {
          this.snackbar = true;
          this.text = "Error fetching users, please refresh";
        }
      );
      return users;
    },
    addUserData(data) {
      let users_arr = [];
      for (let user of data) {
        let users_data = {
          id: user.id,
          name: user.attributes.name,
          email: user.attributes.email,
          user_name: user.attributes.user_name,
          role: user.relationships.role.data.role
        };
        users_arr.push(users_data);
      }
      return users_arr;
    },
    openDialog(item) {
      this.changePasswordItem = item;
      this.dialog = true;
    },
    deleteItem(item) {
      this.$http({
        url: `user/${item.id}`,
        method: "DELETE"
      }).then(
        res => {
          this.getUsersServerSide().then(data => {
            this.users = data.items;
            this.totalUser = data.total;
          });
          this.snackbar = true;
          this.text = "Success deleting user";
        },
        err => {
          this.snackbar = true;
          this.text = "Error deleting user";
        }
      );
    },
    changePassword() {
      if (this.$refs.change_pass_form.validate()) {
        this.$http({
          url: `user/password/${this.changePasswordItem.id}`,
          data: { password: this.password },
          method: "POST"
        }).then(
          res => {
            this.getUsersServerSide().then(data => {
              this.users = data.items;
              this.totalUser = data.total;
            });
            this.snackbar = true;
            this.password = "";
            this.text = "Success changing password";
          },
          err => {
            this.snackbar = true;
            this.password = "";
            this.text = "Error changing password";
          }
        );
      }
      this.changePasswordItem = "";
      this.dialog = false;
    },
    save() {
      if (this.$refs.form.validate()) {
        let user = {
          name: this.name,
          email: this.email,
          user_name: this.user_name,
          password: this.password
        };
        if (this.role == "operator") {
          user.role = "1";
        } else if (this.role == "sender") {
          user.role = "3";
        } else if (this.role == "receiver") {
          user.role = "4";
        }
        this.$http({
          url: `user/create`,
          data: user,
          method: "POST"
        }).then(
          res => {
            this.name = "";
            this.email = "";
            this.password = "";
            this.user_name = "";
            this.role = "";
            this.$refs.form.resetValidation();
            this.getUsersServerSide().then(data => {
              this.users = data.items;
              this.totalUser = data.total;
            });
            this.snackbar = true;
            this.text = "Success adding user";
          },
          err => {
            this.snackbar = true;
            this.text = "Error adding user";
          }
        );
      }
    }
  }
};
</script>
