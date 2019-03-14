<template>
<div class="container">

    <div class="row mt-4" v-if="$gate.isAdminOrOperator()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-primary" @click="newModel">Create New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Crated At</th>
                    <th>Action</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | myDate}}</td>
                    <td>
                        <a href="#" @click="editUser(user)">
                            <i class="fas fa-edit blue"></i>
                        </a>
                        /
                       <a href="#" @click="deleteUser(user.id)">
                            <i class="fas fa-trash red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>



    <div class="row mt-4" v-if="!$gate.isAdminOrOperator()">
    <not-found></not-found>
    </div>
    <!-- modal create user -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="addNew">New User</h5>
                <h5 v-show="editmode" class="modal-title" id="addNew">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form @submit.prevent="editmode ? updateUser() : createUser()" @keydown="form.onKeydown($event)">
                    <div class="form-group">
                    <input v-model="form.name" type="text" name="name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Full Name">
                    <has-error :form="form" field="name"></has-error>
                    </div>

                    <div class="form-group">
                    <input v-model="form.email" type="email" name="email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email Address">
                    <has-error :form="form" field="email"></has-error>
                    </div>

                    <div class="form-group">
                    <input v-model="form.password" type="password" name="password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
                    <has-error :form="form" field="password"></has-error>
                    </div>

                    <div class="form-group">
                    <textarea v-model="form.bio" type="text" name="bio"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">Your Bio (optional)</textarea>
                    <has-error :form="form" field="bio"></has-error>
                    </div>

                    <div class="form-group">
                    <select v-model="form.type" name="type"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                      <option value="">Select Role User</option>
                      <option value="admin">Admin</option>
                      <option value="operator">Operator</option>
                      <option value="user">User</option>
                    </select>
                  </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button v-show="editmode" type="button" class="btn btn-success">Update User</button>
                <button v-show="!editmode" type="button" class="btn btn-primary">Create User</button>
            </div>
            </div>
        </div>
        </div>

    </div>
</template>

<script>
    import * as Fire from "vue";

    export default {
        data() {
            return{
                editmode :false,
                users :{},
                form : new Form({
                    id : '',
                    name : '',
                    email : '',
                    password : '',
                    bio : '',
                    type : '',
                    photo : '',
                })
            }
        },
        methods: {
            getResults(page = 1) {
			axios.get('api/user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
		    },
            updateUser(){
                // console.log('test');
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(()=> {

                    ff.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    Toast.fire({
                    type: 'success',
                    title: 'Signed in successfully'
                    })

                    this.$Progress.finish();
                })
                .catch(()=>{
                    //error
                     this.$Progress.fail();
                });
            },

            editUser(user) {
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show')
                this.form.fill(user);
            },

            newModel() {
                this.editmode = false;
                this.form.reset()
                $('#addNew').modal('show')
            },
             deleteUser(id){
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                               if (result.value) {
                                this.form.delete('api/user/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                        ff.$emit('AfterCreate');
                                }).catch(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'gagal dihapus.',
                                        'success'
                                        )
                                });
                            }
                        })
                },

            loadUsers(){
                if(this.$gate.isAdminOrOperator()){
                    axios.get("api/user").then(({ data }) => (this.users = data));
                }
            },

            createUser() {
                this.$Progress.start();
                this.form.post ('/api/user').then(() => {

                    ff.$emit('AfterCreate');

                    $('#addNew').modal('hide')

                    Toast.fire({
                    type: 'success',
                    title: 'Signed in successfully'
                    })

                    this.$Progress.finish();

                })
                .catch((error)=>{
                    Toast.fire({
                    type: 'error',
                    title: error.message
                    })
                });

            },
        },
        created() {
             ff.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findUser?q=' + query)
                .then((data) => {
                    this.users = data.data
                })
                .catch(() => {
                })
            })
            this.loadUsers();
            ff.$on('AfterCreate',() => {
                this.loadUsers();
           });
        },
    }
</script>
