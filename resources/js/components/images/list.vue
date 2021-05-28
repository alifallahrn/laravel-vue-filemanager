<template>
	<div>
		<div class="card mt-5">
			<div class="card-header">List Images</div>
			<div class="card-body">
				<table
					class="table table-borderless"
					style="width: 100% !important"
				>
					<thead>
						<tr>
							<th>Thumbnail</th>
							<th>Title</th>
							<th>Size</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="!this.images.length">
							<td colspan="4">No images found!</td>
						</tr>
						<tr
							v-else
							v-for="(image, key) in this.images"
							:key="key"
						>
							<th>
								<a
									:href="'/storage/uploads/' + image.name"
									target="_blank"
									><img
										:src="
											'/storage/uploads/thumbnail/' +
											image.name
										"
										class="img-thumbnail"
								/></a>
							</th>
							<td>
								<a
									:href="'/storage/uploads/' + image.name"
									target="_blank"
									>{{ image.title }}</a
								>
							</td>
							<td
								v-text="Math.round(image.size / 1024) + ' KB'"
							></td>
							<td>
								<button
									class="btn btn-sm btn-danger"
									@click="deleteImage(image.id)"
								>
									Delete
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";
	export default {
		props: ["images"],
		methods: {
			deleteImage(id) {
				if (!confirm("Are you sure?!")) return;
				axios
					.delete("/api/image/delete/" + id)
					.then((res) => {
						this.$emit("updateImagesList");
					})
					.catch((err) => {
						console.log(err);
					});
			},
		},
	};
</script>
