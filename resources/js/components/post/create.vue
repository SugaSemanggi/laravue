<template>
<div class="container mt-4">

    <!-- form Input Post -->

            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Create Good Article</h3>
              </div>
              <div class="card-body">

                <!-- /.form group -->
                    <div class="form-group">
                        <input type="text" v-model="form.name" name="name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Category Name">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                <!-- /.form group -->

              </div>
              <!-- /.card-body -->
            </div>

</div>
</template>
<script>
    export default {
        data() {
            return {
                form: new Form ({
                    name: '',
                })
            }
        }
    }
</script>

