// Dữ liệu địa chỉ Việt Nam - Tỉnh/Thành phố và Quận/Huyện
const vietnamAddresses = {
    'An Giang': ['Long Xuyên', 'Châu Đốc', 'An Phú', 'Châu Phú', 'Châu Thành', 'Chợ Mới', 'Phú Tân', 'Tân Châu', 'Thoại Sơn', 'Tri Tôn', 'Tịnh Biên'],
    'Bà Rịa - Vũng Tàu': ['Vũng Tàu', 'Bà Rịa', 'Châu Đức', 'Côn Đảo', 'Đất Đỏ', 'Long Điền', 'Tân Thành', 'Xuyên Mộc'],
    'Bạc Liêu': ['Bạc Liêu', 'Hồng Dân', 'Phước Long', 'Vĩnh Lợi', 'Giá Rai', 'Đông Hải', 'Hoà Bình'],
    'Bắc Giang': ['Bắc Giang', 'Yên Thế', 'Tân Yên', 'Lạng Giang', 'Lục Nam', 'Lục Ngạn', 'Sơn Động', 'Yên Dũng', 'Việt Yên', 'Hiệp Hoà'],
    'Bắc Kạn': ['Bắc Kạn', 'Pác Nặm', 'Ba Bể', 'Ngân Sơn', 'Bạch Thông', 'Chợ Đồn', 'Chợ Mới', 'Na Rì'],
    'Bắc Ninh': ['Bắc Ninh', 'Yên Phong', 'Quế Võ', 'Tiên Du', 'Từ Sơn', 'Thị xã Từ Sơn', 'Thành phố Bắc Ninh'],
    'Bến Tre': ['Bến Tre', 'Châu Thành', 'Chợ Lách', 'Mỏ Cày Bắc', 'Mỏ Cày Nam', 'Giồng Trôm', 'Bình Đại', 'Ba Tri', 'Thạnh Phú', 'Châu Thành'],
    'Bình Định': ['Quy Nhon', 'An Lão', 'Hoài Ân', 'Hoài Nhơn', 'Phù Cát', 'Phù Mỹ', 'Tây Sơn', 'Tuy Phước', 'Vân Canh', 'Vĩnh Thạnh'],
    'Bình Dương': ['Thủ Dầu Một', 'Dầu Tiếng', 'Bến Cát', 'Dầu Tiếng', 'Tân Uyên', 'Dĩ An', 'Thuận An', 'Bắc Tân Uyên'],
    'Bình Phước': ['Đồng Xoài', 'Bình Long', 'Bù Đăng', 'Bù Đốp', 'Bù Gia Mập', 'Chơn Thành', 'Đồng Phú', 'Hớn Quản', 'Lộc Ninh'],
    'Bình Thuận': ['Phan Thiết', 'La Gi', 'Tuy Phong', 'Bắc Bình', 'Hàm Thuận Bắc', 'Hàm Thuận Nam', 'Hàm Tân', 'Đức Linh', 'Tánh Linh', 'Phú Quí'],
    'Cà Mau': ['Cà Mau', 'Cái Nước', 'Đầm Dơi', 'Ngọc Hiển', 'Năm Căn', 'Phú Tân', 'Thới Bình', 'Trần Văn Thời', 'U Minh'],
    'Cần Thơ': ['Ninh Kiều', 'Ô Môn', 'Bình Thuỷ', 'Cái Răng', 'Thốt Nốt', 'Vĩnh Thạnh', 'Cờ Đỏ', 'Phong Điền', 'Thới Lai'],
    'Cao Bằng': ['Cao Bằng', 'Bảo Lạc', 'Bảo Lâm', 'Hạ Lang', 'Hà Quảng', 'Hoà An', 'Nguyên Bình', 'Quảng Uyên', 'Thạch An', 'Trùng Khánh'],
    'Đà Nẵng': ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang', 'Hoàng Sa'],
    'Đắk Lắk': ['Buôn Ma Thuột', 'Buôn Hồ', 'Ea H\'leo', 'Ea Kar', 'Ea Súp', 'Krông A Na', 'Krông Bông', 'Krông Búk', 'Krông Năng', 'Krông Pắk', 'Lắk', 'M\'Đrắk'],
    'Đắk Nông': ['Gia Nghĩa', 'Cư Jút', 'Đắk Glong', 'Đắk Mil', 'Đắk R\'Lấp', 'Đắk Song', 'Krông Nô', 'Tuy Đức'],
    'Điện Biên': ['Điện Biên Phủ', 'Mường Lay', 'Mường Nhé', 'Mường Chà', 'Tủa Chùa', 'Tuần Giáo', 'Điện Biên', 'Điện Biên Đông', 'Mường Ảng', 'Nậm Pồ'],
    'Đồng Nai': ['Biên Hòa', 'Long Khánh', 'Cẩm Mỹ', 'Định Quán', 'Long Thành', 'Nhơn Trạch', 'Tân Phú', 'Thống Nhất', 'Vĩnh Cửu', 'Xuân Lộc'],
    'Đồng Tháp': ['Cao Lãnh', 'Sa Đéc', 'Tam Nông', 'Tân Hồng', 'Tân Hưng', 'Tân Nông', 'Thanh Bình', 'Tháp Mười', 'Lấp Vò', 'Lai Vung', 'Châu Thành'],
    'Gia Lai': ['Pleiku', 'An Khê', 'Ayun Pa', 'Chư Păh', 'Chư Prông', 'Chư Sê', 'Đăk Đoa', 'Đăk Pơ', 'Đức Cơ', 'Ia Grai', 'Ia Pa', 'KBang', 'Kông Chro', 'Krông Pa', 'Mang Yang', 'Phú Thiện'],
    'Hà Giang': ['Hà Giang', 'Bắc Mê', 'Bắc Quang', 'Đồng Văn', 'Hoàng Su Phì', 'Mèo Vạc', 'Mù Cang Chải', 'Quản Bạ', 'Quang Bình', 'Vị Xuyên', 'Xín Mần', 'Yên Minh'],
    'Hà Nam': ['Phủ Lý', 'Bình Lục', 'Duy Tiên', 'Kim Bảng', 'Lý Nhân', 'Thanh Liêm'],
    'Hà Nội': ['Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy', 'Đống Đa', 'Hai Bà Trưng', 'Hoàng Mai', 'Thanh Xuân', 'Sóc Sơn', 'Đông Anh', 'Gia Lâm', 'Nam Từ Liêm', 'Bắc Từ Liêm', 'Mê Linh', 'Hà Đông', 'Sơn Tây', 'Ba Vì', 'Phúc Thọ', 'Đan Phượng', 'Hoài Đức', 'Quốc Oai', 'Thạch Thất', 'Chương Mỹ', 'Thanh Oai', 'Thường Tín', 'Phú Xuyên', 'Ứng Hòa', 'Mỹ Đức'],
    'Hà Tĩnh': ['Hà Tĩnh', 'Hồng Lĩnh', 'Can Lộc', 'Cẩm Xuyên', 'Đức Thọ', 'Hương Khê', 'Hương Sơn', 'Kỳ Anh', 'Lộc Hà', 'Nghi Xuân', 'Thạch Hà', 'Vũ Quang'],
    'Hải Dương': ['Hải Dương', 'Chí Linh', 'Bình Giang', 'Cẩm Giàng', 'Gia Lộc', 'Kim Thành', 'Kinh Môn', 'Nam Sách', 'Ninh Giang', 'Thanh Hà', 'Thanh Miện', 'Tứ Kỳ'],
    'Hải Phòng': ['Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Hải An', 'Kiến An', 'Đồ Sơn', 'Thuỷ Nguyên', 'An Dương', 'An Lão', 'Kiến Thuỵ', 'Tiên Lãng', 'Vĩnh Bảo', 'Cát Hải', 'Bạch Long Vĩ'],
    'Hậu Giang': ['Vị Thanh', 'Ngã Bảy', 'Châu Thành', 'Châu Thành A', 'Long Mỹ', 'Phụng Hiệp', 'Vị Thủy', 'Xuyên Mộc'],
    'Hòa Bình': ['Hòa Bình', 'Đà Bắc', 'Kim Bôi', 'Cao Phong', 'Lạc Sơn', 'Lạc Thủy', 'Lương Sơn', 'Mai Châu', 'Tân Lạc', 'Yên Thủy'],
    'Hưng Yên': ['Hưng Yên', 'Văn Lâm', 'Văn Giang', 'Yên Mỹ', 'Mỹ Hào', 'Ân Thi', 'Khoái Châu', 'Kim Động', 'Tiên Lữ', 'Phù Cừ', 'Trần Cao'],
    'Khánh Hòa': ['Nha Trang', 'Cam Ranh', 'Cam Lâm', 'Diên Khánh', 'Khánh Sơn', 'Khánh Vĩnh', 'Ninh Hòa', 'Trường Sa', 'Vạn Ninh'],
    'Kiên Giang': ['Rạch Giá', 'Hà Tiên', 'An Biên', 'An Minh', 'Châu Thành', 'Giồng Riềng', 'Gò Quao', 'Hòn Đất', 'Kiên Hải', 'Kiên Lương', 'Phú Quốc', 'Tân Hiệp', 'U Minh Thượng', 'Vĩnh Thuận'],
    'Kon Tum': ['Kon Tum', 'Đắk Glei', 'Đắk Hà', 'Đắk Tô', 'Ia H\'Drai', 'Kon Plông', 'Kon Rẫy', 'Ngọc Hồi', 'Sa Thầy', 'Tu Mơ Rông'],
    'Lai Châu': ['Lai Châu', 'Mường Tè', 'Nậm Nhùn', 'Phong Thổ', 'Sìn Hồ', 'Tam Đường', 'Tân Uyên', 'Than Uyên'],
    'Lâm Đồng': ['Đà Lạt', 'Bảo Lộc', 'Bảo Lâm', 'Cát Tiên', 'Đạ Huoai', 'Đạ Tẻh', 'Đam Rông', 'Đơn Dương', 'Đức Trọng', 'Lạc Dương', 'Lâm Hà'],
    'Lạng Sơn': ['Lạng Sơn', 'Bắc Sơn', 'Bình Gia', 'Cao Lộc', 'Chi Lăng', 'Đình Lập', 'Hữu Lũng', 'Lộc Bình', 'Tràng Định', 'Văn Lãng', 'Văn Quan'],
    'Lào Cai': ['Lào Cai', 'Bát Xát', 'Bảo Thắng', 'Bảo Yên', 'Bắc Hà', 'Mường Khương', 'Sa Pa', 'Si Ma Cai', 'Văn Bàn'],
    'Long An': ['Tân An', 'Kiến Tường', 'Bến Lức', 'Cần Đước', 'Cần Giuộc', 'Châu Thành', 'Đức Hòa', 'Đức Huệ', 'Mộc Hóa', 'Tân Hưng', 'Tân Thạnh', 'Tân Trụ', 'Thạnh Hóa', 'Thủ Thừa', 'Vĩnh Hưng'],
    'Nam Định': ['Nam Định', 'Mỹ Lộc', 'Vụ Bản', 'Ý Yên', 'Nghĩa Hưng', 'Nam Trực', 'Trực Ninh', 'Xuân Trường', 'Giao Thủy', 'Hải Hậu'],
    'Nghệ An': ['Vinh', 'Cửa Lò', 'Thái Hòa', 'Hoàng Mai', 'Anh Sơn', 'Con Cuông', 'Diễn Châu', 'Đô Lương', 'Hưng Nguyên', 'Kỳ Sơn', 'Nam Đàn', 'Nghi Lộc', 'Nghĩa Đàn', 'Quế Phong', 'Quỳ Châu', 'Quỳ Hợp', 'Quỳnh Lưu', 'Tân Kỳ', 'Thanh Chương', 'Tương Dương', 'Yên Thành'],
    'Ninh Bình': ['Ninh Bình', 'Tam Điệp', 'Nho Quan', 'Gia Viễn', 'Hoa Lư', 'Yên Khánh', 'Kim Sơn', 'Yên Mô'],
    'Ninh Thuận': ['Phan Rang-Tháp Chàm', 'Bác Ái', 'Ninh Hải', 'Ninh Phước', 'Ninh Sơn', 'Thuận Bắc', 'Thuận Nam'],
    'Phú Thọ': ['Việt Trì', 'Phú Thọ', 'Cẩm Khê', 'Đoan Hùng', 'Hạ Hòa', 'Lâm Thao', 'Phù Ninh', 'Tam Nông', 'Tân Sơn', 'Thanh Ba', 'Thanh Sơn', 'Thanh Thủy', 'Yên Lập'],
    'Phú Yên': ['Tuy Hòa', 'Sông Cầu', 'Đông Hòa', 'Phú Hòa', 'Sơn Hòa', 'Sông Hinh', 'Tây Hòa', 'Tuy An'],
    'Quảng Bình': ['Đồng Hới', 'Ba Đồn', 'Bố Trạch', 'Lệ Thủy', 'Minh Hóa', 'Quảng Ninh', 'Quảng Trạch', 'Tuyên Hóa'],
    'Quảng Nam': ['Tam Kỳ', 'Hội An', 'Bắc Trà My', 'Đại Lộc', 'Đông Giang', 'Duy Xuyên', 'Hiệp Đức', 'Nam Giang', 'Nam Trà My', 'Phước Sơn', 'Phú Ninh', 'Tây Giang', 'Thăng Bình', 'Tiên Phước'],
    'Quảng Ngãi': ['Quảng Ngãi', 'Ba Tơ', 'Bình Sơn', 'Đức Phổ', 'Lý Sơn', 'Minh Long', 'Mộ Đức', 'Nghĩa Hành', 'Sơn Hà', 'Sơn Tịnh', 'Sơn Tây', 'Tây Trà', 'Trà Bồng', 'Tư Nghĩa'],
    'Quảng Ninh': ['Hạ Long', 'Móng Cái', 'Cẩm Phả', 'Uông Bí', 'Bình Liêu', 'Cô Tô', 'Đầm Hà', 'Đông Triều', 'Hải Hà', 'Hoành Bồ', 'Quảng Yên', 'Tiên Yên', 'Vân Đồn'],
    'Quảng Trị': ['Đông Hà', 'Quảng Trị', 'Cam Lộ', 'Cồn Cỏ', 'Đa Krông', 'Gio Linh', 'Hải Lăng', 'Hướng Hóa', 'Triệu Phong', 'Vĩnh Linh'],
    'Sóc Trăng': ['Sóc Trăng', 'Châu Thành', 'Cù Lao Dung', 'Kế Sách', 'Long Phú', 'Mỹ Tú', 'Mỹ Xuyên', 'Ngã Năm', 'Thạnh Trị', 'Trần Đề', 'Vĩnh Châu'],
    'Sơn La': ['Sơn La', 'Mai Sơn', 'Mộc Châu', 'Mường La', 'Mường Tè', 'Phù Yên', 'Quỳnh Nhai', 'Sông Mã', 'Sốp Cộp', 'Súp Nưa', 'Thuận Châu', 'Vân Hồ', 'Yên Châu'],
    'Tây Ninh': ['Tây Ninh', 'Bến Cầu', 'Châu Thành', 'Dương Minh Châu', 'Gò Dầu', 'Hòa Thành', 'Tân Biên', 'Tân Châu', 'Trảng Bàng'],
    'Thái Bình': ['Thái Bình', 'Đông Hưng', 'Hưng Hà', 'Kiến Xương', 'Quỳnh Phụ', 'Thái Thụy', 'Tiền Hải', 'Vũ Thư'],
    'Thái Nguyên': ['Thái Nguyên', 'Sông Công', 'Đại Từ', 'Định Hóa', 'Đồng Hỷ', 'Phú Bình', 'Phú Lương', 'Võ Nhai'],
    'Thanh Hóa': ['Thanh Hóa', 'Bỉm Sơn', 'Sầm Sơn', 'Bá Thước', 'Cẩm Thủy', 'Đông Sơn', 'Hà Trung', 'Hậu Lộc', 'Hoằng Hóa', 'Lang Chánh', 'Mường Lát', 'Nga Sơn', 'Ngọc Lặc', 'Như Thanh', 'Như Xuân', 'Nông Cống', 'Quan Hóa', 'Quan Sơn', 'Quảng Xương', 'Thạch Thành', 'Thiệu Hóa', 'Thọ Xuân', 'Thường Xuân', 'Tĩnh Gia', 'Triệu Sơn', 'Vĩnh Lộc', 'Yên Định'],
    'Thừa Thiên Huế': ['Huế', 'Hương Thủy', 'Hương Trà', 'A Lưới', 'Nam Đông', 'Phong Điền', 'Phú Lộc', 'Phú Vang', 'Quảng Điền'],
    'Tiền Giang': ['Mỹ Tho', 'Gò Công', 'Cai Lậy', 'Cái Bè', 'Châu Thành', 'Chợ Gạo', 'Gò Công Đông', 'Gò Công Tây', 'Tân Phú Đông', 'Tân Phước'],
    'Trà Vinh': ['Trà Vinh', 'Càng Long', 'Cầu Kè', 'Cầu Ngang', 'Châu Thành', 'Duyên Hải', 'Tiểu Cần', 'Trà Cú'],
    'Tuyên Quang': ['Tuyên Quang', 'Lâm Bình', 'Na Hang', 'Chiêm Hóa', 'Hàm Yên', 'Sơn Dương', 'Yên Sơn'],
    'Vĩnh Long': ['Vĩnh Long', 'Bình Minh', 'Bình Tân', 'Long Hồ', 'Mang Thít', 'Tam Bình', 'Trà Ôn', 'Vũng Liêm'],
    'Vĩnh Phúc': ['Vĩnh Yên', 'Phúc Yên', 'Bình Xuyên', 'Lập Thạch', 'Sông Lô', 'Tam Đảo', 'Tam Dương', 'Vĩnh Tường', 'Yên Lạc'],
    'Yên Bái': ['Yên Bái', 'Nghĩa Lộ', 'Lục Yên', 'Mù Cang Chải', 'Trạm Tấu', 'Trấn Yên', 'Văn Chấn', 'Văn Yên', 'Yên Bình'],
    'Hồ Chí Minh': ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10', 'Quận 11', 'Quận 12', 'Bình Thạnh', 'Gò Vấp', 'Phú Nhuận', 'Tân Bình', 'Tân Phú', 'Bình Tân', 'Thủ Đức', 'Nhà Bè', 'Cần Giờ', 'Hóc Môn', 'Củ Chi', 'Bình Chánh']
};

// Hàm tìm kiếm tỉnh/thành phố từ địa chỉ
function findProvinceFromAddress(address) {
    if (!address) return null;
    
    const addressLower = address.toLowerCase();
    
    // Tìm kiếm trực tiếp
    for (const province in vietnamAddresses) {
        const provinceLower = province.toLowerCase();
        if (addressLower.includes(provinceLower)) {
            return province;
        }
    }
    
    // Tìm kiếm với các biến thể tên
    const provinceVariants = {
        'hà nội': 'Hà Nội',
        'ha noi': 'Hà Nội',
        'hanoi': 'Hà Nội',
        'hồ chí minh': 'Hồ Chí Minh',
        'ho chi minh': 'Hồ Chí Minh',
        'hcm': 'Hồ Chí Minh',
        'sài gòn': 'Hồ Chí Minh',
        'saigon': 'Hồ Chí Minh',
        'đà nẵng': 'Đà Nẵng',
        'da nang': 'Đà Nẵng',
        'danang': 'Đà Nẵng',
        'hải phòng': 'Hải Phòng',
        'hai phong': 'Hải Phòng',
        'haiphong': 'Hải Phòng',
        'cần thơ': 'Cần Thơ',
        'can tho': 'Cần Thơ',
        'cantho': 'Cần Thơ',
        'an giang': 'An Giang',
        'bà rịa - vũng tàu': 'Bà Rịa - Vũng Tàu',
        'vũng tàu': 'Bà Rịa - Vũng Tàu',
        'vung tau': 'Bà Rịa - Vũng Tàu',
        'bạc liêu': 'Bạc Liêu',
        'bắc giang': 'Bắc Giang',
        'bắc kạn': 'Bắc Kạn',
        'bắc ninh': 'Bắc Ninh',
        'bến tre': 'Bến Tre',
        'bình định': 'Bình Định',
        'bình dương': 'Bình Dương',
        'bình phước': 'Bình Phước',
        'bình thuận': 'Bình Thuận',
        'cà mau': 'Cà Mau',
        'cao bằng': 'Cao Bằng',
        'đắk lắk': 'Đắk Lắk',
        'đắk nông': 'Đắk Nông',
        'điện biên': 'Điện Biên',
        'đồng nai': 'Đồng Nai',
        'đồng tháp': 'Đồng Tháp',
        'gia lai': 'Gia Lai',
        'hà giang': 'Hà Giang',
        'hà nam': 'Hà Nam',
        'hà tĩnh': 'Hà Tĩnh',
        'hải dương': 'Hải Dương',
        'hậu giang': 'Hậu Giang',
        'hòa bình': 'Hòa Bình',
        'hưng yên': 'Hưng Yên',
        'khánh hòa': 'Khánh Hòa',
        'kiên giang': 'Kiên Giang',
        'kon tum': 'Kon Tum',
        'lai châu': 'Lai Châu',
        'lâm đồng': 'Lâm Đồng',
        'lạng sơn': 'Lạng Sơn',
        'lào cai': 'Lào Cai',
        'long an': 'Long An',
        'nam định': 'Nam Định',
        'nghệ an': 'Nghệ An',
        'ninh bình': 'Ninh Bình',
        'ninh thuận': 'Ninh Thuận',
        'phú thọ': 'Phú Thọ',
        'phú yên': 'Phú Yên',
        'quảng bình': 'Quảng Bình',
        'quảng nam': 'Quảng Nam',
        'quảng ngãi': 'Quảng Ngãi',
        'quảng ninh': 'Quảng Ninh',
        'quảng trị': 'Quảng Trị',
        'sóc trăng': 'Sóc Trăng',
        'sơn la': 'Sơn La',
        'tây ninh': 'Tây Ninh',
        'thái bình': 'Thái Bình',
        'thái nguyên': 'Thái Nguyên',
        'thanh hóa': 'Thanh Hóa',
        'thừa thiên huế': 'Thừa Thiên Huế',
        'tiền giang': 'Tiền Giang',
        'trà vinh': 'Trà Vinh',
        'tuyên quang': 'Tuyên Quang',
        'vĩnh long': 'Vĩnh Long',
        'vĩnh phúc': 'Vĩnh Phúc',
        'yên bái': 'Yên Bái'
    };
    
    for (const variant in provinceVariants) {
        if (addressLower.includes(variant)) {
            return provinceVariants[variant];
        }
    }
    
    return null;
}

// Hàm tìm kiếm quận/huyện từ địa chỉ
function findDistrictFromAddress(address, province) {
    if (!address || !province) return null;
    
    const addressLower = address.toLowerCase();
    const districts = vietnamAddresses[province] || [];
    
    // Tìm kiếm trực tiếp
    for (const district of districts) {
        const districtLower = district.toLowerCase();
        if (addressLower.includes(districtLower)) {
            return district;
        }
    }
    
    // Tìm kiếm với các biến thể
    const districtVariants = {
        'quận 1': 'Quận 1',
        'quan 1': 'Quận 1',
        'q1': 'Quận 1',
        'quận 2': 'Quận 2',
        'quan 2': 'Quận 2',
        'q2': 'Quận 2',
        'quận 3': 'Quận 3',
        'quan 3': 'Quận 3',
        'q3': 'Quận 3',
        'quận 4': 'Quận 4',
        'quan 4': 'Quận 4',
        'q4': 'Quận 4',
        'quận 5': 'Quận 5',
        'quan 5': 'Quận 5',
        'q5': 'Quận 5',
        'quận 6': 'Quận 6',
        'quan 6': 'Quận 6',
        'q6': 'Quận 6',
        'quận 7': 'Quận 7',
        'quan 7': 'Quận 7',
        'q7': 'Quận 7',
        'quận 8': 'Quận 8',
        'quan 8': 'Quận 8',
        'q8': 'Quận 8',
        'quận 9': 'Quận 9',
        'quan 9': 'Quận 9',
        'q9': 'Quận 9',
        'quận 10': 'Quận 10',
        'quan 10': 'Quận 10',
        'q10': 'Quận 10',
        'quận 11': 'Quận 11',
        'quan 11': 'Quận 11',
        'q11': 'Quận 11',
        'quận 12': 'Quận 12',
        'quan 12': 'Quận 12',
        'q12': 'Quận 12',
        'ba đình': 'Ba Đình',
        'hoàn kiếm': 'Hoàn Kiếm',
        'tây hồ': 'Tây Hồ',
        'long biên': 'Long Biên',
        'cầu giấy': 'Cầu Giấy',
        'đống đa': 'Đống Đa',
        'hai bà trưng': 'Hai Bà Trưng',
        'hoàng mai': 'Hoàng Mai',
        'thanh xuân': 'Thanh Xuân',
        'bình thạnh': 'Bình Thạnh',
        'gò vấp': 'Gò Vấp',
        'phú nhuận': 'Phú Nhuận',
        'tân bình': 'Tân Bình',
        'tân phú': 'Tân Phú',
        'bình tân': 'Bình Tân',
        'thủ đức': 'Thủ Đức',
        'nhà bè': 'Nhà Bè',
        'cần giờ': 'Cần Giờ',
        'hóc môn': 'Hóc Môn',
        'củ chi': 'Củ Chi',
        'bình chánh': 'Bình Chánh'
    };
    
    for (const variant in districtVariants) {
        if (addressLower.includes(variant)) {
            const foundDistrict = districtVariants[variant];
            if (districts.includes(foundDistrict)) {
                return foundDistrict;
            }
        }
    }
    
    return null;
}

// Hàm tự động điền địa chỉ
function autoFillAddress(addressText) {
    if (!addressText) return;
    
    const province = findProvinceFromAddress(addressText);
    const district = province ? findDistrictFromAddress(addressText, province) : null;
    
    if (province) {
        const provinceSelect = document.getElementById('province');
        if (provinceSelect) {
            provinceSelect.value = province;
            // Trigger change event để cập nhật quận/huyện
            provinceSelect.dispatchEvent(new Event('change'));
        }
    }
    
    if (district) {
        const districtSelect = document.getElementById('district');
        if (districtSelect) {
            districtSelect.value = district;
        }
    }
}

