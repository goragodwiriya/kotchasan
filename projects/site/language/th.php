<?php
/**
 * site/language/th.php
 * Thai text for the {LNG_...} placeholders. The key is the English text,
 * so Language::get('Home') gives 'หน้าแรก' here and 'Home' in English.
 */
return [
    // Used by Kotchasan\Date
    'DATE_FORMAT' => 'd M Y เวลา H:i น.',
    'DATE_LONG' => ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
    'DATE_SHORT' => ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
    'MONTH_LONG' => [1 => 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
    'MONTH_SHORT' => [1 => 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
    'YEAR_OFFSET' => 543,

    // Layout (themes/default/index.html)
    'All examples' => 'ตัวอย่างทั้งหมด',
    'Website with templates' => 'เว็บไซต์ด้วย Template',
    'View the source' => 'ดูซอร์สโค้ด',
    'Main menu' => 'เมนูหลัก',
    'Language' => 'ภาษา',
    'Coffee from Thai farms, roasted every week and brewed with care.' => 'กาแฟจากไร่ไทย คั่วใหม่ทุกสัปดาห์ และชงอย่างใส่ใจ',
    'A coffee shop that exists only in this example.' => 'ร้านกาแฟที่มีอยู่จริงแค่ในตัวอย่างนี้',
    'Made with' => 'สร้างด้วย',

    // Menu
    'Home' => 'หน้าแรก',
    'Our menu' => 'เมนู',
    'About us' => 'เกี่ยวกับเรา',
    'Contact' => 'ติดต่อเรา',
    'Page not found' => 'ไม่พบหน้าที่ต้องการ',

    // Home page
    'Fresh roast every Monday' => 'คั่วใหม่ทุกวันจันทร์',
    'Good coffee, slow mornings' => 'กาแฟดี ๆ กับเช้าที่ไม่ต้องรีบ',
    'See our menu' => 'ดูเมนู',
    'Find us' => 'ที่ตั้งร้าน',
    'Thai beans' => 'เมล็ดกาแฟไทย',
    'Arabica from small farms in the hills of Chiang Mai and Chiang Rai.' => 'อาราบิกาจากไร่เล็ก ๆ บนดอยในเชียงใหม่และเชียงราย',
    'Roasted weekly' => 'คั่วทุกสัปดาห์',
    'We roast in small batches, so every cup is fresh.' => 'เราคั่วครั้งละไม่มาก กาแฟทุกแก้วจึงสดใหม่',
    'A quiet place' => 'มุมสงบ ๆ',
    'Good light, free Wi-Fi and a power socket at every table.' => 'แสงสวย มี Wi-Fi ฟรี และมีปลั๊กไฟทุกโต๊ะ',
    'How this website is built' => 'เว็บไซต์นี้สร้างอย่างไร',
    'is the layout of every page: the header, the menu and the footer.' => 'คือเลย์เอาต์ที่ทุกหน้าใช้ร่วมกัน ได้แก่ ส่วนหัว เมนู และส่วนท้าย',
    'is the content of this page. The controller puts it where the layout has' => 'คือเนื้อหาของหน้านี้ Controller นำไปวางในเลย์เอาต์ตรงตำแหน่ง',
    'becomes the text of the current language, from' => 'ถูกแทนที่ด้วยข้อความของภาษาที่เลือก จากไฟล์',
    'or' => 'หรือ',
    'The menu and the TH / EN links are built in' => 'เมนูและลิงก์ TH / EN สร้างขึ้นใน',

    // Menu page
    'Hot or iced' => 'ร้อนหรือเย็น',
    'Every drink can be hot or iced, and oat milk costs nothing extra.' => 'ทุกเมนูสั่งได้ทั้งร้อนและเย็น เปลี่ยนเป็นนมโอ๊ตไม่มีค่าใช้จ่ายเพิ่ม',
    'Each drink is one copy of' => 'แต่ละเมนูคือ',
    'filled in by' => 'หนึ่งชุดที่เติมค่าด้วย',
    'Espresso' => 'เอสเปรสโซ',
    'A short, strong shot of our house blend' => 'กาแฟช็อตเข้มข้นจากเมล็ดเบลนด์ของร้าน',
    'Thai iced coffee' => 'กาแฟเย็น',
    'Dark roast with sweet condensed milk over ice' => 'คั่วเข้ม ใส่นมข้นหวาน เสิร์ฟพร้อมน้ำแข็ง',
    'Iced latte' => 'ลาเต้เย็น',
    'A double shot with cold fresh milk' => 'เอสเปรสโซสองช็อตกับนมสดเย็น ๆ',
    'Cold brew' => 'โคลด์บรูว์',
    'Steeped for 16 hours, smooth and light' => 'สกัดเย็นนาน 16 ชั่วโมง นุ่มและละมุน',
    'Thai tea' => 'ชาไทย',
    'Brewed strong and topped with fresh milk' => 'ชงเข้มข้น เติมนมสด',
    'Cocoa' => 'โกโก้',
    'Rich dark chocolate, hot or iced' => 'ดาร์กช็อกโกแลตเข้มข้น ร้อนหรือเย็นก็ได้',

    // About page
    'Our story' => 'เรื่องของเรา',
    'A small shop with a big roaster' => 'ร้านเล็ก ๆ กับเครื่องคั่วตัวใหญ่',
    'Chang Noi started as a coffee cart next to a school. Parents stopped every morning, and the cart soon needed a roof.' => 'ช้างน้อยเริ่มจากรถเข็นขายกาแฟหน้าโรงเรียน ผู้ปกครองแวะซื้อทุกเช้า ไม่นานรถเข็นก็ต้องมีหลังคา',
    'Today we buy green beans straight from the farmers who grow them, roast them ourselves and serve them in a shop with eight tables.' => 'วันนี้เราซื้อเมล็ดกาแฟดิบจากเกษตรกรผู้ปลูกโดยตรง คั่วเอง และเสิร์ฟในร้านที่มีแปดโต๊ะ',
    'partner farms' => 'ไร่ที่ร่วมงานกับเรา',
    'drinks on the menu' => 'เมนูเครื่องดื่ม',
    'tables' => 'โต๊ะในร้าน',

    // Contact page
    'Come and visit' => 'แวะมาหาเรา',
    'Where to find us' => 'ที่ตั้งและการติดต่อ',
    'Address' => 'ที่อยู่',
    '99 Example Road, Bangkok 10000' => '99 ถนนตัวอย่าง กรุงเทพมหานคร 10000',
    'Phone' => 'โทรศัพท์',
    'Email' => 'อีเมล',
    'Opening hours' => 'เวลาเปิดร้าน',
    'Monday to Friday' => 'จันทร์ – ศุกร์',
    'Saturday and Sunday' => 'เสาร์ – อาทิตย์',
    'Public holidays' => 'วันหยุดนักขัตฤกษ์',
    'Closed' => 'ปิด',

    // 404
    'The page you asked for does not exist.' => 'ไม่มีหน้าที่คุณเรียก',
    'Back to the home page' => 'กลับหน้าแรก'
];
