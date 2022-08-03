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
        saveDocumentCall(data, callBackHandler) {
            axios({
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                method: 'post',
                url: '/api/admin/documents/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        updateDocumentCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/documents/update',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
                .catch(error => {
                    return callBackHandler(error);
                });
        },
        removeDocumentCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/admin/documents/remove/' + id,
            }).then(response => {
                return callBackHandler(response);
            })
                .catch(error => {
                    return callBackHandler(error);
                });
        },
        getDocumentCategoriesCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/documents/categories/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        createCategoryCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/documents/categories/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        updateCategoryCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/documents/categories/update',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        removeCategoryCall(id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/admin/documents/categories/'+id+'/delete',
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
    }
}