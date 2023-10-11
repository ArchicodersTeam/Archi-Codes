function getZIPCodeByAddress(streetAddress, state) {
    const searchUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(streetAddress)},${encodeURIComponent(state)}`

    return new Promise(function (resolve, reject) {
        fetch(searchUrl)
            .then(response => {
                if (!response.ok) {
                    console.error('Error in the Nominatim search request:', response.status)
                    reject(response.status)
                } else {
                    return response.json()
                }
            })
            .then(data => {
                if (data.length > 0) {
                    const placeId = data[0].place_id
                    const detailsUrl = `https://nominatim.openstreetmap.org/details.php?place_id=${placeId}&format=json`

                    fetch(detailsUrl)
                        .then(detailsResponse => {
                            if (!detailsResponse.ok) {
                                console.error('Error in the details.php request:', detailsResponse.status)
                                reject(detailsResponse.status)
                            } else {
                                return detailsResponse.json()
                            }
                        })
                        .then(detailsData => {
                            if (detailsData) {
                                try {
                                    const zipCode = detailsData.calculated_postcode
                                    resolve(zipCode)
                                } catch (error) {
                                    reject(error)
                                }
                            } else {
                                console.log('No address details found for the given place_id.')
                                reject(null)
                            }
                        })
                        .catch(error => {
                            console.error('Error in the details.php request:', error)
                            reject(error)
                        })
                } else {
                    console.log('No results found for the given address.')
                    reject(null)
                }
            })
            .catch(error => {
                console.error('Error in the Nominatim search request:', error)
                reject(error)
            })
    })
}

getZIPCodeByAddress('1600 Amphitheatre Parkway', 'Mountain View, CA')

async function test() {
    console.log(await getZIPCodeByAddress('1600 Amphitheatre Parkway', 'Mountain View, CA'))
}

test()