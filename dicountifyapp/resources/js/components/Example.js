import React from 'react';
import ReactDOM from 'react-dom';
import '@shopify/polaris/build/esm/styles.css';
import {AppProvider, Page, Link, Card, DataTable} from '@shopify/polaris';
import axios from 'axios';

function Example() {
    
    var productrows = [];
    var shop = Shopify.shop;
    axios.get(`https://dicountifyapp.herokuapp.com/product_discount_page_content?shop=${shop}`)
    .then(response => {
        var data = response.data;
        for(var k in data) {
            console.log(k, data[k]);
            productrows[k] = [
                <img src={data[k]['image']} style={{height:'40px',width:'40px'}} />,
                data[k]['title'],
                <Link to={data[k]['action']} removeUnderline url={data[k]['action']} >View</Link>
            ]
        }
        localStorage.setItem('discountproduct',  JSON.stringify(productrows));
        console.log(productrows, 'productrows');
    })
    var discountproduct = localStorage.getItem('discountproduct');
    console.log(productrows, 'this is rows');

    return (
        <AppProvider>
            <Page title="Product's Discount">
                <Card>
                    <DataTable
                    columnContentTypes={[
                        'text',
                        'text',
                        'numeric'
                    ]}
                    headings={[
                        'Image',
                        'Title',
                        'Action'
                    ]}
                    rows={productrows}
                    />
                </Card>
            </Page>
        </AppProvider>
    );

}

export default Example;

if (document.getElementById('example')) {
    ReactDOM.render(<Example />, document.getElementById('example'));
}
