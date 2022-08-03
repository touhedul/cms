export default {
    methods: {
        getDocumentsCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/documents/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
    }
}