<style>
widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px;
}
</style>


<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">

               <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background: url('./img/background.jpg') center center;">
                <h3 class="widget-user-username">{{ this.form.name }}</h3>
                <h5 class="widget-user-desc">{{this.form.name}}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getProfile()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>

            </div>
        </div>

        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                      <!-- activity -->
                      <h1> Activity Soon </h1>
                  </div>
                  <!-- /.tab-pane -->


                  <div class="tab-pane active show" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                          <input type="text" v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" id="name" placeholder="Name">
                             <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">

                    <input v-model="form.email" type="email" name="email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email Address">
                    <has-error :form="form" field="email"></has-error>

                        </div>
                      </div>

                      <div class="form-group">
                        <label for="bio" class="col-sm-2 control-label">Bio</label>

                        <div class="col-sm-10">
                          <textarea class="form-control" v-model="form.bio" :class="{ 'is-invalid': form.errors.has('bio') }" id="bio" placeholder="Bio. . . "></textarea>
                             <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>

                    <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label">Photo</label>
                            <div class="col-sm-10">
                                <input type="file" @change="updateProfile" class="form-control" name="photo">
                            </div>
                    </div>

                     <div class="form-group">
                        <label for="password" class="col-sm-5 control-label">Password <strong>(leave if you not changing)</strong> </label>

                        <div class="col-sm-10">
                          <input type="password" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" id="password" placeholder="password">
                             <has-error :form="form" field="password"></has-error>
                          <!-- <small>  </small> -->
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
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
            getProfile(){
                let photo = (this.form.photo.length > 200 ) ? this.form.photo : 'img/profile/'+ this.form.photo;
                return photo;
            },
            updateInfo(){
                this.$Progress.start();
                if(this.form.password == ''){
                    this.form.password = undefined;
                }
                this.form.put('api/profile')
                    .then(() => {
                        // ff.$emit('AfterCreate');
                        this.$Progress.finis();
                    })
                    .catch(() => {

                         this.$Progress.fail();
                    })
            },
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                if(file['size'] < 2111774 ){
                    reader.onloadend = (file) => {
                        // console.log('Result', reader.result)
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Your upload large file',
                    })
                }
            }
        },

        mounted() {
            console.log('Component mounted.')
        },

        created() {
            axios.get("api/profile")
            .then(({data}) => (this.form.fill(data)));
        },
    }
</script>
