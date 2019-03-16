<template>
<div class="container">

    <div class="row mt-4" v-if="$gate.isAdminOrOperator()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Categories Table</h3>

                <div class="card-tools">
                    <router-link to="/post-create" class="btn btn-primary" @click="newModel">Create New Category <i class="fas fa-user-plus fa-fw"></i></router-link>
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
                  <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <!-- <img :src="getPhoto(category.category_image)" width="150px" :alt="category.name"> -->
                    </td>
                    <td></td>
                    <td>
                        <a href="#">
                            <i class="fas fa-edit blue"></i>
                        </a>
                        /
                       <a href="#">
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
    </div>
</template>
