<template>
<div class="container">

    <div class="row mt-4" v-if="$gate.isAdminOrOperator()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Categories Table</h3>

                <div class="card-tools">
                    <button class="btn btn-primary" @click="newModel">Create New Category <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Create At</th>
                    <th>Action</th>
                  </tr>
                  <tr v-for="category in categories.data" :key="category.id">
                    <td>{{ category.id }}</td>
                    <td>{{ category.name }}</td>
                    <td>
                        <img :src="getPhoto(category.category_image)" width="150px" :alt="category.name">
                    </td>
                    <td>{{ category.created_at | myDate }} </td>
                    <td>
                        <a href="#" @click="editCategory(category)">
                            <i class="fas fa-edit blue"></i>
                        </a>
                        /
                       <a href="#" @click="deleteCategory(category.id)">
                            <i class="fas fa-trash red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
              </div>
            </div>
            <!-- /.card -->
          </div>
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
            <form @submit.prevent="editmode ? updateCategory() : createCategory()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
                    <div class="form-group">
                    <input type="text" v-model="form.name" name="name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Category Name">
                    <has-error :form="form" field="name"></has-error>
                    </div>

                    <div class="form-group">
                    <label for="category_image"> Category Image </label>
                    <input type="file" @change="getImage" name="category_image"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('category_image') }" placeholder="Category Image">
                    <has-error :form="form" field="category_image"></has-error>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
            </div>
            </form>
            </div>
        </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            editmode :false,
            categories: {},
            form : new Form({
                id : '',
                name:'',
                category_image:'',
            })
        }
    },
    methods:{
        newModel() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show')
        },

        getImage(e){
            let file = e.target.files[0];
            let reader = new FileReader();
            if( file['size'] < 2111774 ) {
                reader.onloadend = (file) => {
                    this.form.category_image = reader.result
                }
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Your Upload Large File',
                })
            }
        },
        createCategory() {
                this.$Progress.start();
                this.form.post ('/api/category').then(() => {

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

        loadCategory() {
            this.$Progress.start();
            axios.get("api/category").then(({data})=>(this.categories = data));
        },

        getPhoto(image){
                return './img/category/'+image;
        },

        deleteCategory(id){
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
                    this.form.delete('api/category/'+id).then(()=>{
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

        editCategory(category) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show')
            this.form.fill(category);
        },

        updateCategory(){
            this.$Progress.start();
            this.form.put('api/category/'+this.form.id)
            .then(()=>{
                ff.$emit('AfterCreate');
                $('#addNew').modal('hide')
                Toast.fire({
                    type: 'success',
                    title: 'Category Update'
                })
                this.$Progress.finish();
            })
            .catch(()=>{
                this.$Progress.fail();
            })
        }
    },
    created() {
        this.loadCategory();
        ff.$on('AfterCreate',() => {
            this.loadCategory();
        });
    }
}
</script>
