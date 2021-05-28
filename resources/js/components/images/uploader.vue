<template>
	<div>
		<div class="card mt-5">
			<div class="card-header">Upload Images</div>
			<div class="card-body">
				<div v-if="this.errors.length">
					<div
						class="alert alert-danger"
						v-for="(value, key) in this.errors"
						:key="key"
					>
						{{ value }}
					</div>
				</div>
				<div v-if="this.message">
					<div class="alert alert-success">
						{{ this.message }}
					</div>
				</div>
				<form class="form-horizontal" @submit.prevent="onSubmit">
					<div class="form-group">
						<input
							type="file"
							class="form-control"
							@change="onChange"
						/>
					</div>
					<div class="form-group mb-0">
						<button type="submit" class="btn btn-primary">
							Upload
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";
	export default {
		data: function () {
			return {
				image: false,
				errors: {},
				message: false,
			};
		},
		methods: {
			onChange(e) {
				this.image = e.target.files[0];
			},
			onSubmit() {
				this.errors = {};
				let data = new FormData();
				data.append("image", this.image);
				axios
					.post("/api/image/upload", data, {
						headers: {
							"content-type": "multipart/form-data",
						},
					})
					.then((res) => {
						this.message = res.data.message;
                        this.$emit("updateImagesList");
					})
					.catch((errors) => {
						this.errors = Object.values(
							errors.response.data.errors
						).flat();
					});
			},
		},
	};
</script>
