<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Buyerprotect
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Torsten Walluhn <tw@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
/**
 * Buyer Protection Soap Interface
 *
 * @category  Symmetrics
 * @package   Symmetrics_Buyerprotect
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Torsten Walluhn <tw@symmetrics.de>
 * @author    Ngoc Anh Doan <nd@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Buyerprotect_Model_Service_Soap
{
    /**
     * Keys of of data:
     *
     * returnValue: return_value
     * tsId: ts_id
     * tsProductId: ts_product_id
     * amount: amount
     * currency: currency
     * paymentType: payment_type
     * buyerEmail: buyer_email
     * shopCustomerID: shop_customer_id
     * shopOrderID: shop_order_id
     * orderDate: order_date
     * wsUser: ws_user
     * wsPassword: ws_password
     *
     * @param Mage_Sales_Model_Order $orderObject Order object
     * 
     * @return Varien_Object
     */
    protected function _initTsSoapDataObject(Mage_Sales_Model_Order $orderObject)
    {
        $tsSoapDataObject = new Varien_Object();

        $tsStoreConfigPaths = array(
            'is_active' => Symmetrics_Buyerprotect_Helper_Data::XML_PATH_TS_BUYERPROTECT_IS_ACTIVE,
            'ts_id' => Symmetrics_Buyerprotect_Helper_Data::XML_PATH_TS_BUYERPROTECT_TS_ID,
            'ws_user' => Symmetrics_Buyerprotect_Helper_Data::XML_PATH_TS_BUYERPROTECT_TS_USER,
            'ws_password' => Symmetrics_Buyerprotect_Helper_Data::XML_PATH_TS_BUYERPROTECT_TS_PASSWORD,
            'wsdl_url' => Symmetrics_Buyerprotect_Helper_Data::XML_PATH_TS_BUYERPROTECT_TS_WSDL_URL
        );

        $tsSoapData = array(
            'is_active' => Mage::getStoreConfig($tsStoreConfigPaths['is_active']),
            'ts_id' => Mage::getStoreConfig($tsStoreConfigPaths['ts_id']),
            'ws_user' => Mage::getStoreConfig($tsStoreConfigPaths['ws_user']),
            'ws_password' => Mage::getStoreConfig($tsStoreConfigPaths['ws_password']),
            'wsdl_url' => Mage::getStoreConfig($tsStoreConfigPaths['wsdl_url']),
            'buyer_email' => $orderObject->getCustomerEmail(),
            'amount' => (double) $orderObject->getGrandTotal(),
            'shop_order_id' => $orderObject->getRealOrderId(),
            'order_date' => str_replace(' ', 'T', $order->getCreatedAt()),
        );

        return $tsSoapDataObject->setData($tsSoapData);
    }

    /**
     * make a protection request to the TrustedShops Soap Api
     *
     * @param Mage_Sales_Model_Order $order order to make a Reqest from
     *
     * @return null
     */
    public function requestForProtection(Mage_Sales_Model_Order $order)
    {
        $orderItemsCollection = clone $order->getItemsCollection();
        /* @var $orderItemsCollection Mage_Sales_Model_Mysql4_Order_Item_Collection */
        $orderItemsCollection->resetData();
        $orderItemsCollection->clear();
        $orderItemsCollection->addFieldToFilter('product_type', array('eq' => 'buyerprotect'));

        $orderItemsCollection->load();

        if ($orderItemsCollection->count() >= 1) {
            $firstItem = $orderItemsCollection->getFirstItem();
            $tsSoapDataObject = $this->_initTsSoapDataObject();


            /** @todo make Soap Call */
        }
    }
}